@extends('layouts.app')

@section('title', 'Contact Us - Ansari Handloom')

@section('content')
<div class="bg-luxury-beige/25 py-8 border-b border-luxury-gold/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-serif font-bold text-luxury-maroon">Contact Our Loom</h1>
        <p class="text-xs text-luxury-charcoal/50 mt-1">Get in touch for wholesale orders, custom weaves, or general inquiries.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="row g-5">
        <!-- Contact Information Details -->
        <div class="col-lg-5 space-y-8">
            <div class="space-y-4">
                <span class="text-xs uppercase tracking-widest text-luxury-gold font-bold block">Weaver Workshop</span>
                <h2 class="text-2xl font-serif font-bold text-luxury-maroon">Get in Touch Directly</h2>
                <p class="text-xs text-luxury-charcoal/70 text-justify leading-relaxed">
                    Have questions about our designs, bulk shipping rates, or customization options? Drop us a line or call our Varanasi center. We value direct developer and wholesale client associations.
                </p>
            </div>

            <div class="space-y-6 text-sm">
                <div class="flex items-center space-x-4">
                    <span class="w-10 h-10 rounded-full bg-luxury-maroon/10 text-luxury-maroon flex items-center justify-center text-lg"><i class="bi bi-geo-alt-fill"></i></span>
                    <div>
                        <strong class="text-xs uppercase text-luxury-gold block">Loom Workshop</strong>
                        <span class="text-xs">Ansari Handloom, Weaver Colony, Varanasi, UP - 221001</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="w-10 h-10 rounded-full bg-luxury-maroon/10 text-luxury-maroon flex items-center justify-center text-lg"><i class="bi bi-envelope-fill"></i></span>
                    <div>
                        <strong class="text-xs uppercase text-luxury-gold block">Email Address</strong>
                        <span class="text-xs">support@ansarihandloom.com</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="w-10 h-10 rounded-full bg-luxury-maroon/10 text-luxury-maroon flex items-center justify-center text-lg"><i class="bi bi-telephone-fill"></i></span>
                    <div>
                        <strong class="text-xs uppercase text-luxury-gold block">Call Center</strong>
                        <span class="text-xs">+91 98765 43210</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form View -->
        <div class="col-lg-7">
            <div class="bg-white border border-luxury-gold/15 rounded-lg p-8 shadow-sm space-y-6">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-3"><i class="bi bi-envelope mr-2"></i> Send Inquiry Message</h3>
                
                @if(session('success'))
                    <div class="alert alert-success text-xs rounded border border-green-300 py-2.5 px-4 bg-green-50 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-xs font-semibold mb-1 block">Full Name *</label>
                            <input type="text" name="name" required placeholder="John Doe" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs font-semibold mb-1 block">Email Address *</label>
                            <input type="email" name="email" required placeholder="john@example.com" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
                        </div>
                        <div class="col-12">
                            <label class="text-xs font-semibold mb-1 block">Phone Number</label>
                            <input type="tel" name="phone" placeholder="9876543210" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
                        </div>
                        <div class="col-12">
                            <label class="text-xs font-semibold mb-1 block">Message Inquiry *</label>
                            <textarea name="message" required rows="4" placeholder="How can we assist you with our handloom weaves?" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none"></textarea>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold py-3 px-8 rounded-full uppercase tracking-wider text-xs transition border border-luxury-gold/10">
                            Submit Inquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
