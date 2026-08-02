@extends('admin.layouts.admin')

@section('title', 'Marketing & CMS Manager - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif font-bold text-luxury-maroon">Marketing & CMS Manager</h1>
            <p class="text-xs text-gray-500 mt-1">Manage promo codes, review approvals, homepage slider, and blogs.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-xs rounded border border-green-300 py-2.5 px-4 bg-green-50 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Coupon Form and List -->
        <div class="col-lg-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-tag-fill mr-2"></i> Create Discount Coupon</h3>
                <form action="{{ route('admin.coupon.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Coupon Code *</label>
                            <input type="text" name="code" required placeholder="e.g. SILK20" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Min Cart Value *</label>
                            <input type="number" name="min_cart_value" required min="0" step="0.01" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Discount Type *</label>
                            <select name="type" required class="w-full border rounded px-3 py-2 outline-none">
                                <option value="percentage">Percentage (%)</option>
                                <option value="flat">Flat Price (₹)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Discount Value *</label>
                            <input type="number" name="value" required min="0" step="0.01" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Start Date *</label>
                            <input type="date" name="start_date" required class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">End Date *</label>
                            <input type="date" name="end_date" required class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-luxury-maroon text-white font-bold py-2.5 rounded uppercase tracking-wider text-[10px] hover:bg-luxury-maroonlight transition mt-2">
                        Create Coupon
                    </button>
                </form>
            </div>

            <!-- Banners Manager -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mt-4 space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-image-fill mr-2"></i> Add Homepage Slide Banner</h3>
                <form action="{{ route('admin.banner.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Banner Title</label>
                            <input type="text" name="title" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Subtitle</label>
                            <input type="text" name="subtitle" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Image Filename *</label>
                            <input type="text" name="image_path" required placeholder="saree_red.jpg" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                        <div class="col-md-4">
                            <label class="font-semibold block mb-1">Click URL Redirect</label>
                            <input type="text" name="click_url" placeholder="/shop?category=sarees" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                        <div class="col-md-2">
                            <label class="font-semibold block mb-1">Order *</label>
                            <input type="number" name="sort_order" required value="0" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-luxury-gold text-luxury-charcoal font-bold py-2.5 rounded uppercase tracking-wider text-[10px] hover:bg-yellow-500 transition">
                        Add Banner Slide
                    </button>
                </form>
            </div>
        </div>

        <!-- Blog Form and Reviews Moderation -->
        <div class="col-lg-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-file-earmark-richtext-fill mr-2"></i> Publish Weaver Story (Blog)</h3>
                <form action="{{ route('admin.blog.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="font-semibold block mb-1">Story Title *</label>
                        <input type="text" name="title" required placeholder="The History of Indigo Dye Weaving" class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Image Filename</label>
                        <input type="text" name="image" placeholder="suit_green.jpg" class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Content Body *</label>
                        <textarea name="content" required rows="5" placeholder="Write the weaver story content details here..." class="w-full border rounded px-3 py-2 outline-none"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-luxury-maroon text-white font-bold py-2.5 rounded uppercase tracking-wider text-[10px] hover:bg-luxury-maroonlight transition">
                        Publish Article
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Active items listing -->
    <div class="row g-4 mt-2">
        <!-- Active Coupons -->
        <div class="col-lg-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-3">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-tag-fill mr-2"></i> Active Promo Coupons</h3>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-xs">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2">Code</th>
                                <th class="py-2">Discount</th>
                                <th class="py-2">Min Cart</th>
                                <th class="py-2">Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($coupons as $coupon)
                                <tr>
                                    <td class="py-3 font-bold text-luxury-gold font-mono">{{ $coupon->code }}</td>
                                    <td class="py-3 text-luxury-charcoal font-bold">
                                        {{ $coupon->type == 'percentage' ? $coupon->value . '%' : '₹' . number_format($coupon->value, 2) }}
                                    </td>
                                    <td class="py-3 text-gray-500 font-mono">₹{{ number_format($coupon->min_cart_value, 2) }}</td>
                                    <td class="py-3 text-gray-500">{{ $coupon->end_date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-gray-400">No coupons active.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Reviews Moderation list -->
        <div class="col-lg-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-3">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-chat-left-text-fill mr-2"></i> Customer Reviews Moderation</h3>
                <div class="space-y-3 max-h-[300px] overflow-y-auto">
                    @forelse($reviews as $rev)
                        <div class="border rounded p-3 bg-gray-50 space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <div>
                                    <strong class="text-luxury-maroon">{{ $rev->customer->name }}</strong>
                                    <span class="text-gray-400 ml-1">on {{ $rev->product->name }}</span>
                                </div>
                                <div class="flex items-center space-x-1 text-luxury-gold font-bold">
                                    <i class="bi bi-star-fill"></i> <span>{{ $rev->rating }}/5</span>
                                </div>
                            </div>
                            <p class="text-gray-600 mb-0 font-serif italic">"{{ $rev->review_text }}"</p>
                            <div class="flex items-center justify-between pt-1">
                                <span class="text-[10px] uppercase font-bold 
                                    {{ $rev->is_approved ? 'text-green-600' : 'text-yellow-600' }}
                                ">
                                    {{ $rev->is_approved ? 'Published' : 'Pending Review' }}
                                </span>
                                @if(!$rev->is_approved)
                                    <form action="{{ route('admin.review.approve', $rev->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-luxury-gold text-luxury-charcoal px-3 py-1 rounded text-[10px] font-bold hover:bg-yellow-500 transition">
                                            Approve Review
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">No reviews submitted yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
