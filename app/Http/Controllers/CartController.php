<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'color_id' => 'nullable|integer',
            'color_name' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        if ($user) {
            // Authenticated user - use database cart
            return $this->addToUserCart($request, $user);
        } else {
            // Guest user - use session cart
            return $this->addToSessionCart($request);
        }
    }

    private function addToUserCart(Request $request, $user)
    {
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Check if same product with same color already exists
        $cartItemQuery = $cart->items()->where('product_id', $request->product_id);
        
        if ($request->color_id) {
            $cartItemQuery->where('color_id', $request->color_id);
        } else {
            $cartItemQuery->whereNull('color_id');
        }
        
        $cartItem = $cartItemQuery->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
            $message = 'Cart quantity updated successfully.';
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'color_id' => $request->color_id,
                'color_name' => $request->color_name,
            ]);
            $message = 'Product added to cart successfully.';
        }

        return response()->json(['message' => $message]);
    }

    private function addToSessionCart(Request $request)
    {
        $sessionCart = Session::get('cart', []);

        $key = $request->product_id . '_' . ($request->color_id ?? 'no_color');

        if (isset($sessionCart[$key])) {
            $sessionCart[$key]['quantity'] += $request->quantity;
            $message = 'Cart quantity updated successfully.';
        } else {
            $sessionCart[$key] = [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'color_id' => $request->color_id,
                'color_name' => $request->color_name,
            ];
            $message = 'Product added to cart successfully.';
        }

        Session::put('cart', $sessionCart);

        return response()->json(['message' => $message]);
    }

    public function getCartItemCount()
    {
        $user = Auth::user();
        
        if ($user) {
            // Authenticated user - get from database
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json(['count' => 0]);
            }

            $count = $cart->items()->sum('quantity');
        } else {
            // Guest user - get from session
            $sessionCart = Session::get('cart', []);
            $count = array_sum(array_column($sessionCart, 'quantity'));
        }

        return response()->json(['count' => $count]);
    }
}