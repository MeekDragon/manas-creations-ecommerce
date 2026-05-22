<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /** List active categories */
    public function index()
    {
        $categories = Category::withCount('products')->ordered()->get();
        $trashedCount = Category::onlyTrashed()->count();
        return view('admin.categories', compact('categories', 'trashedCount'));
    }

    /** Show trashed categories */
    public function trash()
    {
        $categories = Category::onlyTrashed()->withCount('products')->latest('deleted_at')->get();
        $activeCount = Category::count();
        return view('admin.categories-trash', compact('categories', 'activeCount'));
    }

    /** Create form */
    public function create()
    {
        return view('admin.category-form');
    }

    /** Store new category */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:255',
            'icon'        => 'nullable|string|max:10',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'sometimes|boolean',
        ]);

        $data['slug']       = Str::slug($data['name']);
        $data['sort_order']  = $data['sort_order'] ?? 0;
        $data['is_active']   = $request->has('is_active');

        Category::create($data);

        return redirect()->route('admin.categories')->with('success', 'Category created!');
    }

    /** Edit form */
    public function edit(Category $category)
    {
        return view('admin.category-form', compact('category'));
    }

    /** Update existing category */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:255',
            'icon'        => 'nullable|string|max:10',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'sometimes|boolean',
        ]);

        $data['slug']       = Str::slug($data['name']);
        $data['sort_order']  = $data['sort_order'] ?? 0;
        $data['is_active']   = $request->has('is_active');

        $category->update($data);

        return redirect()->route('admin.categories')->with('success', 'Category updated!');
    }

    /** Soft delete a category */
    public function destroy(Category $category)
    {
        // Prevent deleting a category that has products
        if ($category->products()->count() > 0) {
            $msg = "Cannot delete \"{$category->name}\" — it has {$category->products()->count()} product(s). Move or delete them first.";
            if (request()->expectsJson()) {
                return response()->json(['error' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $category->delete(); // soft delete

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.categories')->with('success', "Category \"{$category->name}\" moved to trash.");
    }

    /** Restore a soft-deleted category */
    public function restore(int $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.categories.trash')->with('success', "Category \"{$category->name}\" restored!");
    }

    /** Permanently delete a category */
    public function forceDelete(int $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);

        // Safety check: don't permanently delete if products still linked
        if ($category->products()->withTrashed()->count() > 0) {
            $msg = "Cannot permanently delete \"{$category->name}\" — it still has linked products.";
            if (request()->expectsJson()) {
                return response()->json(['error' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $category->forceDelete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.categories.trash')->with('success', "Category permanently deleted.");
    }
}
