<?php

namespace App\Http\Controllers\Catagory;

use App\Models\Category;
use App\Models\parent_category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function InsertCategory(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories',
          'parent_id' => 'nullable|exists:parent_categories,id',
        'status' => 'required|in:active,inactive,draft',
        'description' => 'nullable|string',
        'meta_title' => 'nullable|string|max:60',
        'meta_description' => 'nullable|string|max:160',
    ]);

    Category::create([
        'name' => $request->name,
        'slug' => $request->slug,
        'parent_id' => $request->parent_id,
        'status' => $request->status,
        'description' => $request->description,
        'meta_title' => $request->meta_title,
        'meta_description' => $request->meta_description,
    ]);

    return response()->json(['message' => 'Category created successfully']);
}







// parent cetagory


    public function InsertParentCategory(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories',
        'parent_id' => 'nullable|exists:categories,id',
        'status' => 'required|in:active,inactive,draft',
        'description' => 'nullable|string',
        'meta_title' => 'nullable|string|max:60',
        'meta_description' => 'nullable|string|max:160',
    ]);

    parent_category::create([
        'name' => $request->name,
        'slug' => $request->slug,
        'parent_id' => $request->parent_id,
        'status' => $request->status,
        'description' => $request->description,
        'meta_title' => $request->meta_title,
        'meta_description' => $request->meta_description,
    ]);

    return response()->json(['message' => 'Category created successfully']);
}


// show cetagory

public function show(){

$parentCetagory = parent_category::get();

return response()->json($parentCetagory);

}



// show cetagory in the table


public function showCetagoryList()
    {
        try {
            $showCetagoryList = Category::select([
                'id',
                'name',
                'slug',
                'description',
                'parent_id',
                'meta_title',
                'meta_description',
                'status',
                'created_at',
                'updated_at'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

            $cetagoryCount = Category::count();

            return response()->json([
                'showCetagoryList' => $showCetagoryList,
                'count' => $cetagoryCount,
                'success' => true
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch categories',
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }


}
