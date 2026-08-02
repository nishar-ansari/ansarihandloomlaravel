@extends('layouts.app')

@section('title', 'Customer Login - Ansari Handloom')

@section('content')
<div class="max-w-md mx-auto my-20 px-4 sm:px-6">
    <div class="bg-white border border-luxury-gold/15 rounded-lg p-8 shadow-md space-y-6">
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-serif font-bold text-luxury-maroon">Welcome Back</h2>
            <div class="w-16 h-0.5 bg-luxury-gold mx-auto"></div>
            <p class="text-xs text-luxury-charcoal/50">Log in to view your orders, wishlist, and reward points.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-xs rounded border border-red-300 py-2.5 px-4 bg-red-50 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('customer.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-xs font-semibold mb-1 block">Email Address</label>
                <input type="email" name="email" required placeholder="john@example.com" value="john@example.com" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="text-xs font-semibold">Password</label>
                    <a href="#" class="text-xxs text-luxury-gold hover:text-luxury-maroon transition no-underline">Forgot Password?</a>
                </div>
                <input type="password" name="password" required placeholder="••••••••" value="password" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold py-3 rounded-full uppercase tracking-wider text-xs transition shadow-sm">
                    Log In
                </button>
            </div>
        </form>

        <div class="relative flex items-center justify-center my-4">
            <div class="border-t border-luxury-gold/15 w-full"></div>
            <span class="absolute bg-white px-3 text-xxs text-luxury-charcoal/40 uppercase tracking-widest">Or</span>
        </div>

        <!-- Mobile OTP Login Toggle -->
        <div class="text-center">
            <p class="text-xs text-luxury-charcoal/60">
                Don't have an account? 
                <a href="#" class="text-luxury-gold hover:text-luxury-maroon font-bold transition no-underline">Register here</a>
            </p>
        </div>
    </div>
</div>
@endsection
