@extends('layouts.app')

@section('title', 'Secure Checkout - Ansari Handloom')

@section('content')
<div class="bg-luxury-beige/25 py-8 border-b border-luxury-gold/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-serif font-bold text-luxury-maroon">Checkout</h1>
        <p class="text-xs text-luxury-charcoal/50 mt-1">Provide your delivery details and place your order.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if(session('cart') && count(session('cart')) > 0)
        <form action="{{ route('checkout.place') }}" method="POST">
            @csrf
            <div class="row g-5">
                <!-- Shipping Address Form -->
                <div class="col-lg-7 space-y-6">
                    <div class="bg-white border border-luxury-gold/10 rounded-lg p-6 shadow-sm space-y-6">
                        <h3 class="text-sm uppercase font-bold text-luxury-maroon font-serif tracking-wider mb-4 border-b border-luxury-gold/10 pb-4">
                            Shipping Information
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Customer Auth Check / Guest Form -->
                            @guest('customer')
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="text-xs font-semibold mb-1 block">Your Name *</label>
                                        <input type="text" name="customer_name" required placeholder="John Doe" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-xs font-semibold mb-1 block">Your Email *</label>
                                        <input type="email" name="customer_email" required placeholder="john@example.com" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
                                    </div>
                                    <div class="col-12">
                                        <label class="text-xs font-semibold mb-1 block">Mobile Number *</label>
                                        <input type="tel" name="customer_phone" required placeholder="9876543210" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
                                    </div>
                                </div>
                            @else
                                <div class="bg-luxury-beige/20 p-4 border border-luxury-gold/10 rounded text-sm">
                                    <p class="font-bold text-luxury-maroon">Ordering as logged-in customer:</p>
                                    <p class="mt-1">Name: {{ auth('customer')->user()->name }} ({{ auth('customer')->user()->email }})</p>
                                </div>
                            @endguest

                            <!-- Address field -->
                            <div>
                                <label class="text-xs font-semibold mb-1 block">Street Address *</label>
                                <textarea name="shipping_address" required rows="3" placeholder="Flat No, Apartment, Street, Locality" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none"></textarea>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-xs font-semibold mb-1 block">City *</label>
                                    <input type="text" name="city" required placeholder="Mumbai" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
                                </div>
                                <div class="col-md-3">
                                    <label class="text-xs font-semibold mb-1 block">State *</label>
                                    <input type="text" name="state" required placeholder="Maharashtra" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
                                </div>
                                <div class="col-md-3">
                                    <label class="text-xs font-semibold mb-1 block">Pincode *</label>
                                    <input type="text" name="pincode" required placeholder="400001" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Review Panel -->
                <div class="col-lg-5">
                    <div class="bg-white border border-luxury-gold/15 rounded-lg p-6 shadow-sm space-y-6 sticky top-28">
                        <h3 class="text-sm uppercase font-bold text-luxury-maroon font-serif tracking-wider mb-4 border-b border-luxury-gold/10 pb-4">
                            Review Items
                        </h3>

                        @php
                            $subtotal = 0;
                            foreach(session('cart') as $details) {
                                $subtotal += $details['price'] * $details['quantity'];
                            }
                            $gst = $subtotal * 0.05;
                            $shipping = $subtotal > 2000 ? 0 : 150;
                            $total = $subtotal + $gst + $shipping;
                        @endphp

                        <div class="space-y-3 max-h-48 overflow-y-auto border-b border-luxury-gold/10 pb-4">
                            @foreach(session('cart') as $details)
                                <div class="flex justify-between items-center text-xs">
                                    <div class="space-y-0.5">
                                        <span class="font-bold text-luxury-maroon block">{{ $details['name'] }}</span>
                                        <span class="text-luxury-charcoal/50">Code: {{ $details['sku_code'] }} &times; {{ $details['quantity'] }}</span>
                                    </div>
                                    <span class="font-bold text-luxury-charcoal">₹{{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pricing Summary -->
                        <div class="space-y-3 text-sm border-b border-luxury-gold/10 pb-4">
                            <div class="flex justify-between">
                                <span class="text-luxury-charcoal/70">Subtotal</span>
                                <span class="font-semibold">₹{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-luxury-charcoal/70">GST (5%)</span>
                                <span class="font-semibold">₹{{ number_format($gst, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-luxury-charcoal/70">Shipping</span>
                                <span class="font-semibold">{{ $shipping == 0 ? 'Free' : '₹' . number_format($shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-base font-bold text-luxury-maroon">
                                <span>Grand Total</span>
                                <span>₹{{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Payment Option -->
                        <div>
                            <h4 class="text-xs uppercase font-bold text-luxury-gold font-serif tracking-wider mb-3">Payment Method</h4>
                            <div class="border border-luxury-gold/20 rounded p-4 bg-luxury-beige/10">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="cod" checked class="accent-luxury-maroon">
                                    <span class="text-sm font-semibold text-luxury-charcoal">Cash on Delivery (COD)</span>
                                </label>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold py-3.5 rounded-full uppercase tracking-wider text-sm transition shadow shadow-lg text-center">
                                Place Secure Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        <script>window.location.href = "{{ route('cart.index') }}";</script>
    @endif
</div>
@endsection
