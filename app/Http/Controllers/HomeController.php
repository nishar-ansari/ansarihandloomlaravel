<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')->where('status', 'active')->orderBy('sort_order')->get();
        
        $featuredProducts = Product::with(['primaryImage', 'brand', 'skus'])
            ->where('status', 'active')
            // Note: is_featured property can be checked or we can just get active ones, wait, we deleted it in the new schema, but wait!
            // In our new schema, did we keep 'is_featured' in products table?
            // Let's check: in `2026_07_29_000002_create_catalog_tables.php`, did we put `is_featured`?
            // No, we removed it from products table because the ChatGPT requirements did not specify it.
            // Oh, we should load just any active products or latest products!
            ->take(4)
            ->get();

        // Fetch dynamic banners
        $banners = Banner::where('is_active', 1)->orderBy('sort_order')->get();

        return view('home', compact('categories', 'featuredProducts', 'banners'));
    }
}
