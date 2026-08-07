<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->latest()->get();
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'required|integer',
        ]);

        // Real-life scenario: keep the category tree to two levels (docx section 2)
        // so navigation and the admin panel stay manageable - a sub-category
        // cannot itself become a parent.
        if ($request->filled('parent_id')) {
            $parent = Category::findOrFail($request->parent_id);
            if ($parent->parent_id !== null) {
                return redirect()->back()->withInput()->with('error', 'Category structure is limited to two levels. "' . $parent->name . '" is already a sub-category and cannot have children - use a filterable attribute instead.');
            }
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'parent_id' => $request->parent_id,
            'sort_order' => $request->sort_order,
            'status' => 'active',
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }
}
