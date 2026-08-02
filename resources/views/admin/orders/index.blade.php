@extends('admin.layouts.admin')

@section('title', 'Orders Management - Ansari Handloom')
@section('page_title', 'Manage Customer Orders')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
        <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-receipt-cutoff mr-2"></i> Orders Pipeline</h3>
    </div>

    <div class="table-responsive">
        <table class="table align-middle text-sm">
            <thead>
                <tr class="text-gray-400 text-xs uppercase">
                    <th>Order No.</th>
                    <th>Customer Name</th>
                    <th>Date & Time</th>
                    <th>Total Amount</th>
                    <th>Order Status</th>
                    <th>Payment Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
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
                        <td>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full 
                                {{ $order->payment_status == 'paid' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
                                {{ $order->payment_status == 'pending' ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' : '' }}
                                {{ $order->payment_status == 'failed' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                                {{ $order->payment_status == 'refunded' ? 'bg-gray-150 text-gray-700 border border-gray-200' : '' }}
                            ">
                                {{ $order->payment_status }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-danger rounded-full px-3.5 py-1 text-xs">Manage &rarr;</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
