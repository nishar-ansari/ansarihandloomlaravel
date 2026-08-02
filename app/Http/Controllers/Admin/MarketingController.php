<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\Banner;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class MarketingController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->get();
        $reviews = Review::with(['product', 'customer'])->latest()->get();
        $banners = Banner::orderBy('sort_order')->get();
        $blogs = BlogPost::with('author')->latest()->get();
        
        return view('admin.marketing.index', compact('coupons', 'reviews', 'banners', 'blogs'));
    }

    public function storeCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'type' => 'required|in:flat,percentage',
            'value' => 'required|numeric|min:0',
            'min_cart_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Coupon::create($request->all());

        return redirect()->route('admin.marketing.index')->with('success', 'Promo Coupon created successfully!');
    }

    public function approveReview($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => 1]);

        return redirect()->route('admin.marketing.index')->with('success', 'Customer review approved for publishing!');
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:150',
            'subtitle' => 'nullable|string|max:255',
            'image_path' => 'required|string|max:255',
            'click_url' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
        ]);

        Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_path' => $request->image_path,
            'click_url' => $request->click_url,
            'sort_order' => $request->sort_order,
            'is_active' => 1,
        ]);

        return redirect()->route('admin.marketing.index')->with('success', 'Homepage banner slide added!');
    }

    public function storeBlog(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'image' => 'nullable|string|max:255',
        ]);

        BlogPost::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'content' => $request->content,
            'image' => $request->image ?: 'saree_blue.jpg',
            'author_id' => Auth::id(),
            'status' => 'published',
        ]);

        return redirect()->route('admin.marketing.index')->with('success', 'Blog post published successfully!');
    }
}
