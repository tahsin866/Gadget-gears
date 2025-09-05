<?php

namespace App\Http\Controllers;
use App\Models\products;

use App\Models\product_images;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class productController extends Controller
{



public function store(Request $request){
    // Validate the request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:255|unique:products',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'nullable|exists:brands,id',
        'shortDescription' => 'nullable|string',
        'longDescription' => 'nullable|string',
        'sale_price' => 'nullable|numeric|min:0',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:255',
        'status' => 'nullable|string|in:active,inactive,draft',
        'featured' => 'nullable|in:0,1,true,false',
        'images' => 'nullable|array|max:10',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'primary_image' => 'nullable|integer|min:0',
        'attributes' => 'nullable|array',
        'attributes.*.name' => 'required_with:attributes.*.value|string|max:255',
        'attributes.*.value' => 'required_with:attributes.*.name|string|max:1000',
        'attributes.*.type' => 'nullable|string|max:255',
    ]);

    try {
        DB::beginTransaction();

        $product = products::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'sku' => $validated['sku'],
            'description' => $validated['shortDescription'] ?? '',
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'stock' => $validated['stock'],
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'featured' => filter_var($validated['featured'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
        ]);

        // Product Images
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $primaryImageIndex = $request->primary_image ?? 0;

            foreach ($images as $index => $image) {
                $imageName = time() . '_' . $index . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('products', $imageName, 'public');

                DB::table('product_images')->insert([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => ($index == $primaryImageIndex) ? 1 : 0,
                ]);
            }
        }

        // Product Attributes & Values
        Log::info('Attributes received:', ['attributes' => $validated['attributes'] ?? 'No attributes']);

        if (!empty($validated['attributes'])) {
            foreach ($validated['attributes'] as $index => $attributeData) {
                try {
                    if (!empty($attributeData['name']) && !empty($attributeData['value'])) {
                        Log::info("Processing attribute {$index}:", $attributeData);

                        // Create new attribute for this product
                        $attributeId = DB::table('attributes')->insertGetId([
                            'name' => $attributeData['name'],
                            'type' => $attributeData['type'] ?? 'text',
                            'product_id' => $product->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Handle comma-separated values
                        $values = array_map('trim', explode(',', $attributeData['value']));

                        foreach ($values as $value) {
                            if (!empty($value)) {
                                DB::table('attribute_values')->insert([
                                    'attribute_id' => $attributeId,
                                    'value' => $value,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }

                        Log::info("Successfully created attribute and values:", [
                            'attribute_id' => $attributeId,
                            'name' => $attributeData['name'],
                            'values' => $values
                        ]);
                    }
                } catch (\Exception $ex) {
                    Log::error('Attribute insert error: ' . $ex->getMessage(), [
                        'attributeData' => $attributeData,
                        'index' => $index
                    ]);
                }
            }
        }

        // Description Points
        if (!empty($request->longDescription)) {
            try {
                $descriptionData = json_decode($request->longDescription, true);

                if (is_array($descriptionData) && !empty($descriptionData)) {
                    foreach ($descriptionData as $index => $point) {
                        if (!empty($point['text']) && !empty($point['type'])) {
                            DB::table('product_description_points')->insert([
                                'product_id' => $product->id,
                                'point_type' => $point['type'],
                                'point_text' => $point['text'],
                                'sort_order' => $index,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            } catch (\Exception $e) {
                if (!empty($request->longDescription)) {
                    DB::table('product_description_points')->insert([
                        'product_id' => $product->id,
                        'point_type' => 'feature',
                        'point_text' => $request->longDescription,
                        'sort_order' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully!',
            'product' => $product
        ], 201);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Product creation failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error creating product: ' . $e->getMessage()
        ], 500);
    }
}





public function productList(){
    try {
        $products = products::with([
            'brand',
            'category',
            'images' => function($query) {
                $query->orderBy('is_primary', 'desc');
            }
        ])
        ->get()
        ->map(function($product) {
            // Get primary image or first image
            $primaryImage = $product->images->where('is_primary', 1)->first();
            $imageUrl = null;

            if ($primaryImage) {
                $imageUrl = ImageHelper::getImageUrl($primaryImage->image_path);
            } elseif ($product->images->count() > 0) {
                $imageUrl = ImageHelper::getImageUrl($product->images->first()->image_path);
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'description' => $product->description,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'stock' => $product->stock,
                'status' => $product->status,
                'featured' => $product->featured,
                'meta_title' => $product->meta_title,
                'meta_description' => $product->meta_description,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
                'brand' => $product->brand,
                'category' => $product->category,
                'image' => $imageUrl,
                'images' => $product->images,
            ];
        });

        return response()->json($products);

    } catch (\Exception $e) {
        Log::error('Error in productList: ' . $e->getMessage());
        return response()->json([
            'error' => 'Failed to fetch products',
            'message' => $e->getMessage()
        ], 500);
    }
}



public function showGrid(){
    try {
        $products = products::with(['brand', 'category'])
            ->leftJoin('product_images', function($join) {
                $join->on('products.id', '=', 'product_images.product_id')
                     ->where('product_images.is_primary', 1);
            })
            ->select(
                'products.*',
                'product_images.image_path as image'
            )
            ->where('products.status', 'active')
            ->get()
            ->map(function($product) {
                // Get colors for this product
                $colors = DB::table('attributes')
                    ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                    ->where('attributes.product_id', $product->id)
                    ->whereIn('attributes.name', ['Color', 'colour', 'color'])
                    ->pluck('attribute_values.value')
                    ->toArray();

                // Get sizes for this product
                $sizes = DB::table('attributes')
                    ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                    ->where('attributes.product_id', $product->id)
                    ->whereIn('attributes.name', ['Size', 'size'])
                    ->pluck('attribute_values.value')
                    ->toArray();

                // Get structured description points
                $descriptionPoints = DB::table('product_description_points')
                    ->where('product_id', $product->id)
                    ->orderBy('sort_order')
                    ->get()
                    ->map(function($point) {
                        return [
                            'id' => $point->id,
                            'type' => $point->point_type,
                            'text' => $point->point_text,
                            'sort_order' => $point->sort_order
                        ];
                    });

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'sku' => $product->sku,
                    'price' => $product->sale_price ?? $product->price,
                    'originalPrice' => $product->sale_price ? $product->price : null,
                    'image' => $product->image ? ImageHelper::getImageUrl($product->image) : 'https://placehold.co/400x300/E5E7EB/9CA3AF/png?text=No+Image',
                    'brand' => $product->brand->name ?? 'No Brand',
                    'category' => $product->category->name ?? 'Uncategorized',
                    'rating' => 4.5, // Default rating - you can implement actual rating system later
                    'reviews' => rand(5, 50), // Random reviews for demo
                    'inStock' => $product->stock > 0,
                    'stock' => $product->stock,
                    'onSale' => $product->sale_price ? true : false,
                    'featured' => $product->featured,
                    'colors' => $colors,
                    'sizes' => $sizes,
                    'descriptionPoints' => $descriptionPoints,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at
                ];
            });

        return response()->json([
            'success' => true,
            'products' => $products,
            'total' => $products->count(),
            'message' => 'Products retrieved successfully'
        ]);

    } catch (\Exception $e) {
        Log::error('Error in showGrid: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'products' => [],
            'total' => 0,
            'message' => 'Error retrieving products'
        ], 500);
    }
}







public function getFeatureTypes(){
    $featureTypes = DB::table('feature_type_icons')
        ->where('is_active', true)
        ->orderBy('type')
        ->get();

    return response()->json($featureTypes);
}






}
