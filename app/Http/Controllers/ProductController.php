<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::whereNull('parent_id')->where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();

        $query = Product::with(['primaryImage', 'brand', 'skus'])->where('status', 'active');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $catSlug = $request->input('category');
            $category = Category::where('slug', $catSlug)->first();
            if ($category) {
                $catIds = [$category->id];
                $childIds = Category::where('parent_id', $category->id)->pluck('id')->toArray();
                $query->whereIn('category_id', array_merge($catIds, $childIds));
            }
        }

        // Brand filter
        if ($request->filled('brand')) {
            $brandSlug = $request->input('brand');
            $brand = Brand::where('slug', $brandSlug)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        // Sorting
        if ($request->filled('sort')) {
            $sort = $request->input('sort');
            if ($sort == 'price_asc') {
                $query->select('products.*')
                    ->join('product_skus', 'products.id', '=', 'product_skus.product_id')
                    ->groupBy('products.id')
                    ->orderByRaw('MIN(product_skus.selling_price) asc');
            } elseif ($sort == 'price_desc') {
                $query->select('products.*')
                    ->join('product_skus', 'products.id', '=', 'product_skus.product_id')
                    ->groupBy('products.id')
                    ->orderByRaw('MIN(product_skus.selling_price) desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->get();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::with([
            'skus.attributeValues.attribute',
            'skus.images',
            'category',
            'brand',
            'attributeSet.attributes.values'
        ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Fetch approved reviews
        $reviews = Review::where('product_id', $product->id)
            ->where('is_approved', 1)
            ->with('customer')
            ->latest()
            ->get();

        // Fetch related products (same category, excluding current product)
        $relatedProducts = Product::with(['primaryImage', 'brand', 'skus'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'reviews', 'relatedProducts'));
    }

    public function submitReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        if (!Auth::guard('customer')->check()) {
            return redirect()->back()->with('error', 'Please log in to submit a review.');
        }

        Review::create([
            'product_id' => $id,
            'customer_id' => Auth::guard('customer')->id(),
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'is_approved' => 0, // Pending moderation
        ]);

        return redirect()->back()->with('success', 'Thank you! Your review has been submitted and is pending moderation.');
    }
}
