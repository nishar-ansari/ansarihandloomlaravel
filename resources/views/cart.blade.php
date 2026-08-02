@extends('layouts.app')

@section('title', 'Shopping Bag - Ansari Handloom')

@section('content')
<div class="bg-luxury-beige/25 py-8 border-b border-luxury-gold/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-serif font-bold text-luxury-maroon">Your Shopping Bag</h1>
        <p class="text-xs text-luxury-charcoal/50 mt-1">Review the artisanal handloom pieces in your cart before checkout.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if(session('cart') && count(session('cart')) > 0)
        <div class="row g-5">
            <!-- Cart Items Grid -->
            <div class="col-lg-8 space-y-4">
                @foreach(session('cart') as $id => $details)
                    <div class="bg-white border border-luxury-gold/10 rounded-lg p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="flex items-center space-x-6">
                            <div class="w-24 aspect-[4/3] rounded overflow-hidden border border-luxury-gold/10 flex-shrink-0 bg-luxury-beige/10">
                                <img src="{{ asset('images/' . $details['image']) }}" class="w-full h-full object-cover" alt="{{ $details['name'] }}">
                            </div>
                            <div>
                                <span class="text-xxs uppercase tracking-widest text-luxury-gold font-bold block mb-0.5">{{ $details['brand'] }}</span>
                                <h3 class="text-sm font-bold font-serif text-luxury-maroon">{{ $details['name'] }}</h3>
                                <span class="text-xxs font-bold text-luxury-charcoal/70 bg-luxury-beige/50 px-2 py-1 rounded inline-block mt-1">Variant: {{ $details['sku_code'] }}</span>
                            </div>
                        </div>

                        <!-- Pricing & Controls -->
                        <div class="flex items-center justify-between sm:justify-end gap-12 w-full sm:w-auto">
                            <div class="text-center sm:text-right">
                                <span class="text-xs text-luxury-charcoal/50 block">Price</span>
                                <span class="text-sm font-bold text-luxury-charcoal">₹{{ number_format($details['price'], 2) }}</span>
                            </div>
                            <div class="text-center">
                                <span class="text-xs text-luxury-charcoal/50 block mb-1">Qty</span>
                                <span class="text-sm font-bold text-luxury-charcoal bg-luxury-beige/20 px-3 py-1 rounded border border-luxury-gold/10">{{ $details['quantity'] }}</span>
                            </div>
                            <div class="text-center sm:text-right">
                                <span class="text-xs text-luxury-charcoal/50 block">Subtotal</span>
                                <span class="text-sm font-bold text-luxury-maroon">₹{{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                            </div>
                            
                            <!-- Remove Item -->
                            <form action="{{ route('cart.remove') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
                                <button type="submit" class="text-danger hover:text-red-700 transition"><i class="bi bi-trash-fill text-lg"></i></button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="bg-white border border-luxury-gold/15 rounded-lg p-6 shadow-sm space-y-6">
                    <h3 class="text-sm uppercase font-bold text-luxury-maroon font-serif tracking-wider mb-4 border-b border-luxury-gold/10 pb-4">Order Summary</h3>
                    
                    @php
                        $subtotal = 0;
                        foreach(session('cart') as $details) {
                            $subtotal += $details['price'] * $details['quantity'];
                        }
                        $gst = $subtotal * 0.05; // 5% GST
                        $shipping = $subtotal > 2000 ? 0 : 150;
                        $total = $subtotal + $gst + $shipping;
                    @endphp

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-luxury-charcoal/70">Bag Subtotal</span>
                            <span class="font-bold">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-luxury-charcoal/70">GST (5%)</span>
                            <span class="font-bold">₹{{ number_format($gst, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-luxury-charcoal/70">Estimated Shipping</span>
                            <span class="font-bold">
                                @if($shipping == 0)
                                    <span class="text-success uppercase tracking-wider text-xxs font-bold">Free</span>
                                @else
                                    ₹{{ number_format($shipping, 2) }}
                                @endif
                            </span>
                        </div>
                        
                        <div class="border-t border-luxury-gold/10 pt-4 flex justify-between text-base font-bold text-luxury-maroon">
                            <span>Order Total</span>
                            <span>₹{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <div class="pt-4">
                        <a href="{{ route('checkout.index') }}" class="w-full bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold py-3.5 rounded-full uppercase tracking-wider text-sm transition shadow shadow-lg text-center no-underline block">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white border border-luxury-gold/10 rounded-lg p-16 text-center shadow-sm">
            <span class="text-6xl text-luxury-gold"><i class="bi bi-bag-x-fill"></i></span>
            <h2 class="text-2xl font-bold font-serif text-luxury-maroon mt-4">Your Shopping Bag is Empty</h2>
            <p class="text-sm text-luxury-charcoal/60 mt-2">Looks like you haven't added any handloom treasures to your bag yet.</p>
            <a href="{{ route('products.index') }}" class="bg-luxury-gold hover:bg-luxury-goldhover text-luxury-charcoal font-bold px-8 py-3 rounded-full uppercase tracking-wider text-xs transition no-underline inline-block mt-6">Shop Collection</a>
        </div>
    @endif
</div>
@endsection
