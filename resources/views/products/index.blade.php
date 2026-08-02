@extends('layouts.app')

@section('title', 'Shop Collection - Ansari Handloom')

@section('content')
<div class="bg-luxury-beige/25 py-8 border-b border-luxury-gold/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-serif font-bold text-luxury-maroon">Our Collections</h1>
        <p class="text-xs text-luxury-charcoal/50 mt-1">Browse authentic direct-from-weaver handwoven sarees, suits, and dress materials.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="bg-white border border-luxury-gold/15 rounded p-6 shadow-sm sticky top-28 space-y-8">
                <!-- Active Filters & Search -->
                <div>
                    <h3 class="text-sm uppercase font-bold text-luxury-maroon font-serif tracking-wider mb-4">Filter Catalog</h3>
                    <form action="{{ route('products.index') }}" method="GET" class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold mb-1 block text-luxury-charcoal/70">Search keyword</label>
                            <div class="flex border border-luxury-gold/25 rounded overflow-hidden text-sm">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="w-full px-3 py-2 outline-none">
                                <button type="submit" class="bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream px-3"><i class="bi bi-search"></i></button>
                            </div>
                        </div>

                        <!-- Reset filters link -->
                        @if(request()->anyFilled(['category', 'brand', 'search']))
                            <a href="{{ route('products.index') }}" class="text-xs text-danger font-semibold no-underline inline-block hover:underline"><i class="bi bi-trash mr-1"></i> Clear all filters</a>
                        @endif
                    </form>
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="text-xs uppercase font-bold text-luxury-gold font-serif tracking-wider mb-3">Categories</h4>
                    <ul class="space-y-2 text-sm p-0 list-none m-0">
                        <li>
                            <a href="{{ route('products.index', request()->except('category')) }}" class="no-underline {{ !request('category') ? 'text-luxury-maroon font-bold' : 'text-luxury-charcoal/70 hover:text-luxury-maroon' }}">
                                All Categories
                            </a>
                        </li>
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('products.index', array_merge(request()->query(), ['category' => $cat->slug])) }}" class="no-underline {{ request('category') == $cat->slug ? 'text-luxury-maroon font-bold' : 'text-luxury-charcoal/70 hover:text-luxury-maroon' }}">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Brands -->
                <div>
                    <h4 class="text-xs uppercase font-bold text-luxury-gold font-serif tracking-wider mb-3">Brands</h4>
                    <ul class="space-y-2 text-sm p-0 list-none m-0">
                        <li>
                            <a href="{{ route('products.index', request()->except('brand')) }}" class="no-underline {{ !request('brand') ? 'text-luxury-maroon font-bold' : 'text-luxury-charcoal/70 hover:text-luxury-maroon' }}">
                                All Brands
                            </a>
                        </li>
                        @foreach($brands as $brand)
                            <li>
                                <a href="{{ route('products.index', array_merge(request()->query(), ['brand' => $brand->slug])) }}" class="no-underline {{ request('brand') == $brand->slug ? 'text-luxury-maroon font-bold' : 'text-luxury-charcoal/70 hover:text-luxury-maroon' }}">
                                    {{ $brand->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-lg-9 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-150 pb-3 text-xs bg-white rounded p-3 shadow-xxs">
                <span class="text-gray-500 font-semibold">Showing {{ $products->count() }} exquisite weaves</span>
                <div class="flex items-center space-x-2">
                    <span class="text-gray-400 font-bold">Sort By:</span>
                    <select onchange="window.location.href = this.value" class="border border-luxury-gold/25 rounded px-2.5 py-1 outline-none text-xs bg-white">
                        <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'latest'])) }}" {{ request('sort') == 'latest' || !request('sort') ? 'selected' : '' }}>Latest Arrivals</option>
                        <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_asc'])) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_desc'])) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="bg-white border border-luxury-gold/10 rounded-lg p-12 text-center shadow-sm">
                    <span class="text-5xl text-luxury-gold"><i class="bi bi-info-circle"></i></span>
                    <h3 class="text-xl font-bold font-serif text-luxury-maroon mt-4">No Products Found</h3>
                    <p class="text-sm text-luxury-charcoal/60 mt-2">Try clearing filters or searching for something else.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-danger mt-4 rounded-full px-6">View All Products</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded overflow-hidden shadow group flex flex-col h-full border border-luxury-gold/5 transition hover:shadow-xl hover:translate-y-[-4px] duration-300">
                            <!-- Image -->
                            <div class="relative overflow-hidden aspect-[4/3] bg-luxury-beige/10">
                                <img src="{{ $product->primaryImage ? asset('images/' . $product->primaryImage->image_path) : asset('images/saree_red.jpg') }}" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                     alt="{{ $product->name }}">
                            </div>

                            <!-- Details -->
                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div>
                                    <span class="text-xxs uppercase tracking-widest text-luxury-gold font-bold block mb-1">{{ $product->brand ? $product->brand->name : 'Handwoven' }}</span>
                                    <h3 class="text-sm font-bold font-serif text-luxury-maroon group-hover:text-luxury-gold transition duration-300 mb-2 line-clamp-2">
                                        <a href="{{ route('products.show', $product->slug) }}" class="no-underline text-inherit">{{ $product->name }}</a>
                                    </h3>
                                    <p class="text-xs text-luxury-charcoal/60 line-clamp-2 mb-4">{{ $product->short_description }}</p>
                                </div>

                                <div class="border-t border-luxury-gold/10 pt-4 mt-2 flex items-center justify-between">
                                    <span class="text-base font-bold text-luxury-charcoal">₹{{ number_format($product->skus->min('selling_price'), 2) }}</span>
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-xs uppercase font-bold text-luxury-maroon hover:text-luxury-gold transition no-underline">Options &rarr;</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
