@extends('layouts.app')

@section('title', 'Ansari Handloom - Weaver Direct Silk Sarees & Salwar Suits')

@section('content')
<!-- Hero Banner Carousel (Bootstrap) -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach($banners as $index => $banner)
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner">
        @foreach($banners as $index => $banner)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }} relative h-[500px] md:h-[600px]" data-bs-interval="5000">
                <img src="{{ asset('images/' . $banner->image_path) }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $banner->title }}">
                <div class="absolute inset-0 bg-black/45"></div>
                <div class="absolute inset-0 flex items-center justify-start max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="max-w-xl text-left text-luxury-cream space-y-6">
                        <span class="text-xs uppercase tracking-widest text-luxury-gold font-semibold block">Exclusive Weaver Direct</span>
                        <h1 class="text-4xl md:text-6xl font-serif font-bold leading-tight">{{ $banner->title }}</h1>
                        <p class="text-sm md:text-base text-luxury-cream/80">{{ $banner->subtitle }}</p>
                        @if($banner->click_url)
                            <div class="pt-4">
                                <a href="{{ $banner->click_url }}" class="bg-luxury-gold hover:bg-luxury-goldhover text-luxury-charcoal font-bold px-8 py-3 rounded-full uppercase tracking-wider text-sm transition no-underline inline-block">Explore Collection</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Features section -->
<div class="bg-luxury-maroon text-luxury-cream py-12 border-b border-luxury-gold/25">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
        <div class="space-y-2 flex flex-col items-center">
            <div class="text-3xl text-luxury-gold mb-2"><i class="bi bi-shield-check"></i></div>
            <h3 class="text-sm font-semibold uppercase tracking-wider">100% Genuine Handloom</h3>
            <p class="text-xs text-luxury-cream/70">Certified authentic silk and fabrics directly from weaver looms.</p>
        </div>
        <div class="space-y-2 flex flex-col items-center">
            <div class="text-3xl text-luxury-gold mb-2"><i class="bi bi-truck"></i></div>
            <h3 class="text-sm font-semibold uppercase tracking-wider">Free Shipping Pan-India</h3>
            <p class="text-xs text-luxury-cream/70">On all orders above ₹2000 with secure courier dispatch.</p>
        </div>
        <div class="space-y-2 flex flex-col items-center">
            <div class="text-3xl text-luxury-gold mb-2"><i class="bi bi-arrow-left-right"></i></div>
            <h3 class="text-sm font-semibold uppercase tracking-wider">7-Day Easy Return</h3>
            <p class="text-xs text-luxury-cream/70">Hassle-free return policy if you're not completely in love.</p>
        </div>
        <div class="space-y-2 flex flex-col items-center">
            <div class="text-3xl text-luxury-gold mb-2"><i class="bi bi-people-fill"></i></div>
            <h3 class="text-sm font-semibold uppercase tracking-wider">Supporting Weaver Families</h3>
            <p class="text-xs text-luxury-cream/70">Every purchase directly supports our weaver community.</p>
        </div>
    </div>
</div>

<!-- Category Showcases -->
<div class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center space-y-2 mb-16">
        <h2 class="text-3xl font-serif font-bold text-luxury-maroon">Shop By Category</h2>
        <div class="w-24 h-0.5 bg-luxury-gold mx-auto"></div>
        <p class="text-sm text-luxury-charcoal/60">Discover curated collections tailored for every event</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach($categories as $cat)
        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="group relative overflow-hidden rounded shadow block h-80 no-underline">
            <!-- Category Image -->
            <div class="absolute inset-0 w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-115" 
                 style="background-image: url('{{ $cat->image ? asset('images/'.$cat->image) : asset('images/saree_red.jpg') }}');"></div>
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent transition-opacity duration-300 group-hover:from-black/90"></div>
            <!-- Category Text -->
            <div class="absolute bottom-6 left-6 text-luxury-cream space-y-1">
                <h3 class="text-xl font-bold font-serif group-hover:text-luxury-gold transition">{{ $cat->name }}</h3>
                <span class="text-xs tracking-widest text-luxury-gold uppercase block">Browse Items &rarr;</span>
            </div>
        </a>
        @endforeach
    </div>
</div>

<!-- Featured Products Section -->
<div class="bg-luxury-beige/30 py-20 border-t border-b border-luxury-gold/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-2 mb-16">
            <h2 class="text-3xl font-serif font-bold text-luxury-maroon">Our Featured Collections</h2>
            <div class="w-24 h-0.5 bg-luxury-gold mx-auto"></div>
            <p class="text-sm text-luxury-charcoal/60">Selected handwoven designs chosen for exceptional artistry</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredProducts as $product)
            <div class="bg-white rounded overflow-hidden shadow group flex flex-col h-full border border-luxury-gold/5 transition hover:shadow-xl hover:translate-y-[-4px] duration-300">
                <!-- Product Image -->
                <div class="relative overflow-hidden aspect-[4/3] bg-luxury-beige/20">
                    <img src="{{ $product->primaryImage ? asset('images/' . $product->primaryImage->image_path) : asset('images/saree_red.jpg') }}" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                         alt="{{ $product->name }}">
                    @if($product->is_featured)
                    <span class="absolute top-4 left-4 bg-luxury-gold text-luxury-charcoal px-3 py-1 rounded-full text-xxs font-bold uppercase tracking-wider shadow">Featured</span>
                    @endif
                </div>

                <!-- Product Body -->
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <span class="text-xxs uppercase tracking-widest text-luxury-gold font-bold block mb-1">{{ $product->brand ? $product->brand->name : 'Handwoven' }}</span>
                        <h3 class="text-sm font-bold font-serif text-luxury-maroon group-hover:text-luxury-gold transition duration-300 mb-2 line-clamp-2">
                            <a href="{{ route('products.show', $product->slug) }}" class="no-underline text-inherit">{{ $product->name }}</a>
                        </h3>
                        <p class="text-xs text-luxury-charcoal/60 line-clamp-2 mb-4">{{ $product->short_description }}</p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between border-t border-luxury-gold/10 pt-4 mt-2">
                            <span class="text-base font-bold text-luxury-charcoal">₹{{ number_format($product->skus->min('selling_price'), 2) }}</span>
                            <a href="{{ route('products.show', $product->slug) }}" class="text-xs uppercase font-semibold text-luxury-maroon hover:text-luxury-gold transition no-underline">View Options &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Our Weaver Story Section -->
<div class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
    <div>
        <img src="{{ asset('images/saree_blue.jpg') }}" class="w-full h-[400px] object-cover rounded shadow-lg" alt="Weaving loom close up">
    </div>
    <div class="space-y-6">
        <span class="text-xs uppercase tracking-widest text-luxury-gold font-bold block">Centuries of Legacy</span>
        <h2 class="text-3xl md:text-4xl font-serif font-bold text-luxury-maroon">Preserving the Art of Handweaving</h2>
        <p class="text-sm text-luxury-charcoal/70 text-justify leading-relaxed">
            Every thread woven in Ansari Handloom carries the soul of Varanasi. We work directly with over 50 weaver families, bypassing middle-men to bring you the highest quality silks, sarees, and suits while ensuring fair wages and preserving the heritage craft.
        </p>
        <p class="text-sm text-luxury-charcoal/70 text-justify leading-relaxed">
            Each saree takes anywhere between 7 to 20 days of meticulous manual work. When you choose Ansari, you own a piece of living history and art.
        </p>
        <div class="pt-2">
            <a href="{{ route('products.index') }}" class="bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold px-8 py-3 rounded-full uppercase tracking-wider text-xs transition no-underline inline-block">Explore Collection</a>
        </div>
    </div>
</div>
@endsection
