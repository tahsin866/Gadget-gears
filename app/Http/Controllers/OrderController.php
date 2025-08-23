<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\products;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Get all orders for admin
     */
    public function index(Request $request)
    {
        try {
            $query = Order::with(['user', 'address', 'orderItems.product', 'orderItems.attributeValue.attribute'])
                ->orderBy('created_at', 'desc');

            // Filter by status if provided
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'LIKE', "%{$search}%")
                      ->orWhere('guest_name', 'LIKE', "%{$search}%")
                      ->orWhere('guest_email', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'LIKE', "%{$search}%")
                                   ->orWhere('email', 'LIKE', "%{$search}%");
                      });
                });
            }

            $orders = $query->get();

            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch orders. ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get specific order details
     */
    public function show($id)
    {
        try {
            $order = Order::with(['user', 'address', 'orderItems.product', 'orderItems.attributeValue.attribute'])
                ->findOrFail($id);

            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Order not found. ' . $e->getMessage()], 404);
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        try {
            $order = Order::findOrFail($id);
            $order->update(['status' => $validated['status']]);

            return response()->json([
                'message' => 'Order status updated successfully!',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update order status. ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete order
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $order = Order::with('orderItems')->findOrFail($id);

            // Restore product stock
            foreach ($order->orderItems as $item) {
                $product = products::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            // Delete order items first due to foreign key constraint
            $order->orderItems()->delete();

            // Delete the order
            $order->delete();

            DB::commit();

            return response()->json(['message' => 'Order deleted successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete order. ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'product_attrs' => 'nullable|array', // Array of selected attribute value IDs
            'product_attrs.*' => 'integer|exists:attribute_values,id', // Validate each attribute value ID
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'shipping_did' => 'required|string|max:255',
            'shipping_des_id' => 'required|string|max:255',
            'shipping_TID' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $product = products::findOrFail($validated['product_id']);

            if ($product->stock < $validated['quantity']) {
                return response()->json(['message' => 'Not enough stock available.'], 400);
            }

            // Calculate pricing
            $price = $product->sale_price ?? $product->price;
            $subtotal = $price * $validated['quantity'];
            $tax = $subtotal * 0.1; // 10% tax
            $shipping = 10; // Fixed shipping cost
            $total = $subtotal + $tax + $shipping;

            $user = Auth::user();
            $addressId = null;

            // If user is authenticated, save address
            if ($user) {
                $address = Address::create([
                    'user_id' => $user->id,
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'],
                    'district' => $validated['shipping_did'],
                    'thana' => $validated['shipping_des_id'],
                    'postoffice' => $validated['shipping_TID'],
                    'address' => $validated['address'],
                    'is_default' => false
                ]);
                $addressId = $address->id;
            }

            // Create order
            $order = Order::create([
                'user_id' => $user?->id,
                'address_id' => $addressId,
                'order_number' => 'GG-' . strtoupper(uniqid()),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'guest_name' => $user ? null : $validated['name'],
                'guest_email' => $user ? null : $validated['email'],
                'guest_phone' => $user ? null : $validated['phone'],
                'shipping_address' => $user ? null : $validated['address'],
                'shipping_did' => $validated['shipping_did'],
                'shipping_des_id' => $validated['shipping_des_id'],
                'shipping_TID' => $validated['shipping_TID'],
            ]);

            // Create order items based on selected attributes
            $productAttrs = $validated['product_attrs'] ?? [];

            Log::info('Order Store - Product Attributes:', ['product_attrs' => $productAttrs]);
            Log::info('Order Store - Validated data:', $validated);

            if (empty($productAttrs)) {
                // No attributes selected, create single order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'product_attr' => null,
                ]);
            } else {
                // Create order item for each selected attribute
                foreach ($productAttrs as $attrValueId) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $validated['quantity'],
                        'price' => $price,
                        'subtotal' => $subtotal,
                        'product_attr' => $attrValueId,
                    ]);
                }
            }

            // Update product stock
            $product->decrement('stock', $validated['quantity']);

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully!',
                'order' => $order,
                'order_number' => $order->order_number
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to place order. ' . $e->getMessage()], 500);
        }
    }
}
