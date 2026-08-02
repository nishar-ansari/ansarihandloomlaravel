@extends('admin.layouts.admin')

@section('title', 'Manage Order ' . $order->order_number . ' - Ansari Handloom')
@section('page_title', 'Order Details')

@section('content')
<div class="row g-4">
    <!-- Order items list -->
    <div class="col-lg-8">
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-cart-check mr-2"></i> Order Items</h3>
                <span class="text-xs text-gray-500 font-semibold">Order Number: <strong>{{ $order->order_number }}</strong></span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle text-sm">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase">
                            <th>SKU Code</th>
                            <th>Product Name</th>
                            <th>Unit Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="font-mono font-bold text-luxury-maroon">{{ $item->productSku->sku_code ?? 'N/A' }}</td>
                                <td>{{ $item->productSku->product->name ?? 'Product' }}</td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="font-bold text-end">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals panel -->
            <div class="border-t border-luxury-gold/10 pt-4 flex flex-col items-end text-sm space-y-1">
                @php
                    $subtotal = 0;
                    foreach($order->items as $item) {
                        $subtotal += $item->price * $item->quantity;
                    }
                    $gst = $subtotal * 0.05;
                    $shipping = $subtotal > 2000 ? 0 : 150;
                @endphp
                <div class="flex justify-between w-64">
                    <span class="text-gray-500">Subtotal:</span>
                    <span class="font-semibold">₹{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between w-64">
                    <span class="text-gray-500">GST (5%):</span>
                    <span class="font-semibold">₹{{ number_format($gst, 2) }}</span>
                </div>
                <div class="flex justify-between w-64">
                    <span class="text-gray-500">Shipping Fee:</span>
                    <span class="font-semibold">{{ $shipping == 0 ? 'Free' : '₹' . number_format($shipping, 2) }}</span>
                </div>
                <div class="flex justify-between w-64 text-base font-bold text-luxury-maroon border-t border-luxury-gold/10 pt-2 mt-2">
                    <span>Grand Total:</span>
                    <span>₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order settings sidebar -->
    <div class="col-lg-4 space-y-4">
        <!-- Customer details -->
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6 space-y-4">
            <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-3"><i class="bi bi-person mr-2"></i> Customer Details</h3>
            <div class="text-xs space-y-2">
                <p><strong>Name:</strong> {{ $order->customer->name ?? 'Guest' }}</p>
                <p><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $order->customer->phone ?? 'N/A' }}</p>
                <div class="border-t border-luxury-gold/10 pt-2 mt-2">
                    <p class="font-bold text-luxury-maroon uppercase tracking-wider mb-1">Shipping Address:</p>
                    <p class="leading-relaxed text-gray-500">{{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>

        <!-- Update status Form -->
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6 space-y-4">
            <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-3"><i class="bi bi-sliders mr-2"></i> Update Order Status</h3>
            
            <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-semibold mb-1 block">Order Lifecycle Status</label>
                    <select name="order_status" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none bg-white">
                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="packed" {{ $order->order_status == 'packed' ? 'selected' : '' }}>Packed</option>
                        <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold mb-1 block">Payment Status</label>
                    <select name="payment_status" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none bg-white">
                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold py-2.5 rounded-full uppercase tracking-wider text-xs transition border border-luxury-gold/10">
                        Save Status Updates
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
