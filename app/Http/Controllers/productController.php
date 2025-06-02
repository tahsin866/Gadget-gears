<?php

namespace App\Http\Controllers;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class productController extends Controller
{



public function store(Request $request)
{
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
    ]);

    products::create([
        'name' => $validated['name'],
        'slug' => Str::slug($validated['name']),
        'sku' => $validated['sku'],
        'description' => $validated['longDescription'] ?? ($validated['shortDescription'] ?? ''),
        'price' => $validated['price'],
        'sale_price' => $validated['sale_price'] ?? null,
        'meta_title' => $validated['meta_title'] ?? null,
        'meta_description' => $validated['meta_description'] ?? null,
        'stock' => $validated['stock'],
        'category_id' => $validated['category_id'],
        'brand_id' => $validated['brand_id'] ?? null,
        'status' => 'active',
        'featured' => null,
    ]);

    return redirect()->back()->with('success', 'Product created successfully!');
}




public function productList(){


    $product = products::get();

    return response()->json($product);
}



}
