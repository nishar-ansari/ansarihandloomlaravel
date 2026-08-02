@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard - Ansari Handloom')
@section('page_title', 'Dashboard Overview')

@section('content')
<!-- Analytical Metric Cards -->
<div class="row g-4 mb-8">
    <!-- Total Sales -->
    <div class="col-md-3">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-luxury-gold/5 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Total Sales</span>
                <span class="text-2xl font-bold text-luxury-maroon">₹{{ number_format($totalSales, 2) }}</span>
            </div>
            <div class="text-3xl text-luxury-gold"><i class="bi bi-currency-rupee"></i></div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-md-3">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-luxury-gold/5 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Total Orders</span>
                <span class="text-2xl font-bold text-luxury-charcoal">{{ $totalOrders }}</span>
            </div>
            <div class="text-3xl text-luxury-gold"><i class="bi bi-cart-check"></i></div>
        </div>
    </div>

    <!-- Active Products -->
    <div class="col-md-3">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-luxury-gold/5 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Active Catalog</span>
                <span class="text-2xl font-bold text-luxury-charcoal">{{ $totalProducts }}</span>
            </div>
            <div class="text-3xl text-luxury-gold"><i class="bi bi-box-seam"></i></div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="col-md-3">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-luxury-gold/5 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Low Stock SKUs</span>
                <span class="text-2xl font-bold {{ $lowStockCount > 0 ? 'text-danger animate-pulse' : 'text-luxury-charcoal' }}">{{ $lowStockCount }}</span>
            </div>
            <div class="text-3xl text-luxury-gold"><i class="bi bi-exclamation-triangle"></i></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders Table -->
    <div class="col-lg-8">
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6 h-full">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-receipt-cutoff mr-2"></i> Recent Customer Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-luxury-gold font-semibold hover:underline no-underline">View All Orders</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle text-sm">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase">
                            <th>Order No.</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td class="font-bold text-luxury-maroon">{{ $order->order_number }}</td>
                                <td>{{ $order->customer->name ?? 'Guest' }}</td>
                                <td class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                <td class="font-semibold">₹{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full 
                                        {{ $order->order_status == 'completed' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
                                        {{ $order->order_status == 'pending' ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' : '' }}
                                        {{ $order->order_status == 'cancelled' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                                        {{ !in_array($order->order_status, ['completed', 'pending', 'cancelled']) ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                    ">
                                        {{ $order->order_status }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-xs btn-outline-danger rounded-full px-3 py-1 text-xs">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Low Stock List -->
    <div class="col-lg-4">
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6 h-full">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-bell-fill mr-2"></i> Low Stock Alerts</h3>
                <a href="{{ route('admin.inventory.index') }}" class="text-xs text-luxury-gold font-semibold hover:underline no-underline">Restock Panel</a>
            </div>

            @if($lowStockItems->isEmpty())
                <div class="text-center py-8 text-sm text-gray-400">
                    <i class="bi bi-shield-check text-4xl text-green-500 block mb-2"></i>
                    <span>All stock levels are optimal!</span>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($lowStockItems as $item)
                        <div class="border-b border-gray-50 pb-3 last:border-b-0 last:pb-0 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-xs text-luxury-maroon block">{{ $item->sku_code }}</span>
                                <span class="text-xxs text-gray-500 line-clamp-1">{{ $item->product->name ?? 'Product' }}</span>
                            </div>
                            <div class="text-end">
                                <span class="text-xs font-bold text-red-600 bg-red-50 border border-red-200 px-2.5 py-0.5 rounded">
                                    {{ $item->stock }} Left
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
