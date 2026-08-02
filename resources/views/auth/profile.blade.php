@extends('layouts.app')

@section('title', 'My Dashboard - Ansari Handloom')

@section('content')
<div class="bg-luxury-beige/25 py-8 border-b border-luxury-gold/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-serif font-bold text-luxury-maroon">My Dashboard</h1>
        <p class="text-xs text-luxury-charcoal/50 mt-1">Manage your account details and view your order history.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="row g-5">
        <!-- Sidebar Profile Details -->
        <div class="col-lg-4">
            <div class="bg-white border border-luxury-gold/15 rounded-lg p-6 shadow-sm space-y-6">
                <div class="text-center space-y-3">
                    <div class="w-20 h-20 rounded-full bg-luxury-maroon text-luxury-gold flex items-center justify-center text-3xl font-bold mx-auto shadow-sm">
                        {{ substr($customer->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold font-serif text-luxury-maroon">{{ $customer->name }}</h3>
                        <span class="text-xxs uppercase tracking-wider text-luxury-gold font-bold">Loyal Patron</span>
                    </div>
                </div>

                <div class="border-t border-luxury-gold/10 pt-4 space-y-3 text-xs">
                    <p><strong>Email Address:</strong> {{ $customer->email }}</p>
                    <p><strong>Phone Number:</strong> {{ $customer->phone ?? 'Not provided' }}</p>
                    <p><strong>Member Since:</strong> {{ $customer->created_at->format('M d, Y') }}</p>
                </div>

                <div class="pt-2 border-t border-luxury-gold/10 text-center">
                    <a href="{{ route('customer.logout') }}" class="text-xs text-danger font-semibold no-underline hover:underline"><i class="bi bi-box-arrow-right mr-1"></i> Log Out Account</a>
                </div>
            </div>
        </div>

        <!-- Orders History -->
        <div class="col-lg-8">
            <div class="bg-white border border-luxury-gold/10 rounded-lg p-6 shadow-sm space-y-6">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-3"><i class="bi bi-receipt mr-2"></i> Order History</h3>

                @if($orders->isEmpty())
                    <div class="text-center py-12 text-gray-400 text-sm">
                        <i class="bi bi-cart-x text-5xl text-luxury-gold block mb-2"></i>
                        <span>You haven't placed any orders yet.</span>
                        <a href="{{ route('products.index') }}" class="text-luxury-maroon font-bold block mt-2 hover:underline">Start shopping &rarr;</a>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($orders as $order)
                            <div class="border border-luxury-gold/10 rounded-lg overflow-hidden bg-gray-50 shadow-xxs">
                                <!-- Order Header -->
                                <div class="bg-luxury-beige/35 border-b border-luxury-gold/10 px-4 py-3 flex flex-wrap items-center justify-between gap-4 text-xs">
                                    <div>
                                        <span class="text-gray-500 mr-2">Order No:</span>
                                        <strong class="text-luxury-maroon font-bold">{{ $order->order_number }}</strong>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 mr-2">Date:</span>
                                        <span class="font-semibold text-luxury-charcoal">{{ $order->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 mr-2">Total Amount:</span>
                                        <strong class="text-luxury-maroon font-bold">₹{{ number_format($order->total_amount, 2) }}</strong>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full 
                                            {{ $order->order_status == 'completed' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
                                            {{ $order->order_status == 'pending' ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' : '' }}
                                            {{ $order->order_status == 'cancelled' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                                            {{ !in_array($order->order_status, ['completed', 'pending', 'cancelled']) ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                        ">
                                            {{ $order->order_status }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Order Items -->
                                <div class="p-4 space-y-3">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center justify-between text-xs">
                                            <div>
                                                <span class="font-bold text-luxury-maroon block">{{ $item->productSku->product->name ?? 'Product' }}</span>
                                                <span class="text-gray-400">Variant: {{ $item->productSku->sku_code }} &times; {{ $item->quantity }}</span>
                                            </div>
                                            <span class="font-bold">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                                        </div>
                                    @endforeach
                                    <div class="border-t border-luxury-gold/5 pt-3 text-[10px] text-gray-400">
                                        <strong>Delivery Address:</strong> {{ $order->shipping_address }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
