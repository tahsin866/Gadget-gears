<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\products;
use App\Models\product_images;
use App\Models\attributes;
use App\Models\attribute_values;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class GadgetGearsController extends Controller
{
    //
// public function buyeProduct($id, Request $request)
//     {
//  $product = products::with([
//     'images',
//     'brand',
//     'attributes.attribute_values' // ✅ nested eager load
// ])->findOrFail($id);


//         return Inertia::render('GADGET-GEARS/buyeProduct', [
//             'product' => $product,
//             'productId' => $id
//         ]);
//     }
















public function buyeProduct($id, Request $request)
{
    $product = products::with([
        'images',
        'brand',
        'attributes.attribute_values'
    ])->findOrFail($id);

    // Load structured description points
    $descriptionPoints = DB::table('product_description_points')
        ->where('product_id', $id)
        ->orderBy('sort_order')
        ->get()
        ->groupBy('point_type')
        ->map(function($points) {
            return $points->map(function($point) {
                return [
                    'id' => $point->id,
                    'text' => $point->point_text,
                    'sort_order' => $point->sort_order
                ];
            });
        });

    // Get feature type icons
    $featureTypes = DB::table('feature_type_icons')
        ->where('is_active', true)
        ->get()
        ->keyBy('type');

    // Add description points to product
    $product->descriptionPoints = $descriptionPoints;
    $product->featureTypes = $featureTypes;

    // ✅ Check if it's a JSON request
    if ($request->wantsJson()) {
        return response()->json($product);
    }

    return Inertia::render('GADGET-GEARS/buyeProduct', [
        'productId' => $id
    ]);
}










    public function orderForm(Request $request)
    {
        $productAttrs = $request->input('product_attrs', []);
        Log::info('OrderForm - Received product_attrs:', ['product_attrs' => $productAttrs]);

        return Inertia::render('GADGET-GEARS/orderForm', [
            'product' => $request->input('product'),
            'quantity' => $request->input('quantity', 1),
            'product_attrs' => $productAttrs
        ]);
    }

    /**
     * Delete a single product
     */
    public function deleteProduct($id)
    {
        try {
            // Find the product
            $product = products::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Check if product is used in orders
            $orderItemsCount = DB::table('order_items')->where('product_id', $id)->count();
            if ($orderItemsCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete product. It is used in ' . $orderItemsCount . ' order(s). Please contact administrator.'
                ], 422);
            }

            // Start database transaction
            DB::beginTransaction();

            // Delete product images from storage and database
            $productImages = DB::table('product_images')->where('product_id', $id)->get();
            foreach ($productImages as $image) {
                // Delete image file from storage
                if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }

            // Delete related data (these don't have orders dependency)
            DB::table('product_images')->where('product_id', $id)->delete();
            DB::table('attributes')->where('product_id', $id)->delete();
            DB::table('product_variants')->where('product_id', $id)->delete();
            DB::table('cart_items')->where('product_id', $id)->delete();
            DB::table('wishlists')->where('product_id', $id)->delete();
            DB::table('reviews')->where('product_id', $id)->delete();

            // Delete the product
            $product->delete();

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete products
     */
    public function bulkDeleteProducts(Request $request)
    {
        try {
            $productIds = $request->input('ids', []);

            if (empty($productIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No products selected for deletion'
                ], 400);
            }

            // Check if any product is used in orders
            $orderItemsCount = DB::table('order_items')->whereIn('product_id', $productIds)->count();
            if ($orderItemsCount > 0) {
                // Get products that are in orders
                $productsInOrders = DB::table('order_items')
                    ->whereIn('product_id', $productIds)
                    ->distinct()
                    ->pluck('product_id')
                    ->toArray();

                $productsInOrdersNames = DB::table('products')
                    ->whereIn('id', $productsInOrders)
                    ->pluck('name')
                    ->toArray();

                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete some products. These products are used in orders: ' . implode(', ', $productsInOrdersNames)
                ], 422);
            }

            // Start database transaction
            DB::beginTransaction();

            $deletedCount = 0;

            foreach ($productIds as $id) {
                $product = products::find($id);
                if ($product) {
                    // Delete product images from storage and database
                    $productImages = DB::table('product_images')->where('product_id', $id)->get();
                    foreach ($productImages as $image) {
                        // Delete image file from storage
                        if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                            Storage::disk('public')->delete($image->image_path);
                        }
                    }

                    // Delete related data (these don't have orders dependency)
                    DB::table('product_images')->where('product_id', $id)->delete();
                    DB::table('attributes')->where('product_id', $id)->delete();
                    DB::table('product_variants')->where('product_id', $id)->delete();
                    DB::table('cart_items')->where('product_id', $id)->delete();
                    DB::table('wishlists')->where('product_id', $id)->delete();
                    DB::table('reviews')->where('product_id', $id)->delete();

                    // Delete the product
                    $product->delete();
                    $deletedCount++;
                }
            }

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount products deleted successfully",
                'deleted_count' => $deletedCount
            ], 200);

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error deleting products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate a product
     */
    public function duplicateProduct($id)
    {
        try {
            $originalProduct = products::with(['images', 'attributes.attribute_values'])->findOrFail($id);

            // Create new product
            $newProduct = products::create([
                'name' => $originalProduct->name . ' (Copy)',
                'slug' => $originalProduct->slug . '-copy-' . time(),
                'sku' => $originalProduct->sku . '-COPY',
                'description' => $originalProduct->description,
                'price' => $originalProduct->price,
                'sale_price' => $originalProduct->sale_price,
                'stock' => 0, // Set stock to 0 for duplicated products
                'category_id' => $originalProduct->category_id,
                'brand_id' => $originalProduct->brand_id,
                'status' => 'draft', // Set status to draft
                'featured' => false,
                'meta_title' => $originalProduct->meta_title,
                'meta_description' => $originalProduct->meta_description,
            ]);

            // Duplicate images
            foreach ($originalProduct->images as $image) {
                product_images::create([
                    'product_id' => $newProduct->id,
                    'image_path' => $image->image_path,
                    'is_primary' => $image->is_primary,
                    'order' => $image->order,
                ]);
            }

            // Duplicate attributes and their values
            foreach ($originalProduct->attributes as $attribute) {
                $newAttribute = attributes::create([
                    'product_id' => $newProduct->id,
                    'attribute_name' => $attribute->attribute_name,
                    'name' => $attribute->name,
                    'type' => $attribute->type,
                ]);

                foreach ($attribute->attribute_values as $value) {
                    attribute_values::create([
                        'attribute_id' => $newAttribute->id,
                        'value' => $value->value,
                    ]);
                }
            }

            // Load the new product with relationships
            $newProduct->load(['images', 'brand', 'attributes.attribute_values']);

            return response()->json($newProduct);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured($id)
    {
        try {
            $product = products::findOrFail($id);
            $product->featured = !$product->featured;
            $product->save();

            return response()->json([
                'success' => true,
                'featured' => $product->featured,
                'message' => 'Featured status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update featured status: ' . $e->getMessage()
            ], 500);
        }
    }
}
