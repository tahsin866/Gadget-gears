<?php

namespace App\Http\Controllers;
use App\Models\brands;
use Illuminate\Http\Request;

class brandController extends Controller
{


public function insertBrand(Request $request){
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:brands',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string'
    ]);

    $logoPath = null;
    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('brands', 'public');
    }

    brands::create([
        'name' => $request->name,
        'slug' => $request->slug,
        'logo' => $logoPath,
        'description' => $request->description
    ]);

    return response()->json(['message' => 'Brand created successfully'], 201);
}





   public function ShowBrand()
{
    $brands = brands::all(); // অথবা Brand::get();
    return response()->json($brands);
}


public function showBrands()
{
    $brands = brands::all();
    return response()->json($brands);
}

public function updateBrand(Request $request, $id)
{
    $brand = brands::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:brands,slug,' . $id,
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string'
    ]);

    $logoPath = $brand->logo;
    if ($request->hasFile('logo')) {
        // Delete old logo if exists
        if ($logoPath && file_exists(storage_path('app/public/' . $logoPath))) {
            unlink(storage_path('app/public/' . $logoPath));
        }
        $logoPath = $request->file('logo')->store('brands', 'public');
    }

    $brand->update([
        'name' => $request->name,
        'slug' => $request->slug,
        'logo' => $logoPath,
        'description' => $request->description
    ]);

    return response()->json(['message' => 'Brand updated successfully'], 200);
}

public function deleteBrand($id)
{
    try {
        $brand = brands::findOrFail($id);

        // Delete logo file if exists
        if ($brand->logo && file_exists(storage_path('app/public/' . $brand->logo))) {
            unlink(storage_path('app/public/' . $brand->logo));
        }

        $brand->delete();

        return response()->json(['message' => 'Brand deleted successfully'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Brand not found or could not be deleted'], 404);
    }
}



}
