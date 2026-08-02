@extends('admin.layouts.admin')

@section('title', 'Customer Directory - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif font-bold text-luxury-maroon">Customer Directory</h1>
            <p class="text-xs text-gray-500 mt-1">Directory of all registered storefront patrons.</p>
        </div>
    </div>

    <!-- Customers Grid List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0 text-xs">
                <thead class="table-light">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">ID</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Customer Name</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Email Address</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Phone Number</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-600">Orders Count</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Member Since</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($customers as $customer)
                        <tr>
                            <td class="px-6 py-4 text-gray-500 font-mono">#{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 font-bold text-luxury-maroon">{{ $customer->name }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $customer->email }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $customer->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-luxury-gold">{{ $customer->orders_count }} orders</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-50 text-green-700 border border-green-200">
                                    {{ $customer->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $customer->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-400">No patrons registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
