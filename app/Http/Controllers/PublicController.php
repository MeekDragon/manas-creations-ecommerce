<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class PublicController extends Controller
{
    public function index()
    {
        $categories = Category::active()->ordered()->get();

        $products = Product::active()
            ->with(['category', 'variants'])
            ->whereHas('category', fn($q) => $q->active())
            ->orderBy('created_at', 'asc')
            ->get();

        $productsByCategory = $products->groupBy(fn($p) => $p->category->name);

        return view('public.home', compact('products', 'categories', 'productsByCategory'));
    }
}
