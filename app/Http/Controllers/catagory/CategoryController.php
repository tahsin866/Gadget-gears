<?php

namespace App\Http\Controllers\catagory;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
    return Inertia::render('GADGET-GEARS/categoryModel', [
        'categories' => $categories
    ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable'
        ]);

        Category::create($validated);

        return redirect()->route('GADGET-GEARS.categoryModel')->with('success', 'Category created successfully');
    }

}
