@extends('admin.layouts.admin')

@section('title', 'Vendors & Procurement - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif font-bold text-luxury-maroon">Vendors & Procurement</h1>
            <p class="text-xs text-gray-500 mt-1">Manage wholesale suppliers, looms, and track purchase orders.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-xs rounded border border-green-300 py-2.5 px-4 bg-green-50 text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-xs rounded border border-red-300 py-2.5 px-4 bg-red-50 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Register Vendor Form -->
        <div class="col-lg-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-person-plus-fill mr-2"></i> Register New Supplier</h3>
                <form action="{{ route('admin.vendors.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="font-semibold block mb-1">Company/Loom Name *</label>
                        <input type="text" name="name" required class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Contact Person Name</label>
                        <input type="text" name="contact_name" class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Email Address</label>
                            <input type="email" name="email" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Phone Number</label>
                            <input type="text" name="phone" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">GSTIN</label>
                        <input type="text" name="gstin" placeholder="09AAAAA1111A1Z1" class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Workshop Address</label>
                        <textarea name="address" rows="2" class="w-full border rounded px-3 py-2 outline-none"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-luxury-maroon text-white font-bold py-2.5 rounded uppercase tracking-wider text-[10px] hover:bg-luxury-maroonlight transition mt-2">
                        Register Vendor
                    </button>
                </form>
            </div>

            <!-- Create Purchase Order Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mt-4 space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-cart-plus-fill mr-2"></i> Issue Purchase Order</h3>
                <form action="{{ route('admin.po.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="font-semibold block mb-1">Select Supplier *</label>
                        <select name="vendor_id" required class="w-full border rounded px-3 py-2 outline-none">
                            <option value="">-- Select Vendor --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Select Product SKU *</label>
                        <select name="product_sku_id" required class="w-full border rounded px-3 py-2 outline-none">
                            <option value="">-- Select SKU --</option>
                            @foreach($skus as $sku)
                                <option value="{{ $sku->id }}">{{ $sku->product->name }} ({{ $sku->sku_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Quantity *</label>
                            <input type="number" name="quantity" required min="1" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                        <div class="col-md-6">
                            <label class="font-semibold block mb-1">Weaving Price (per unit) *</label>
                            <input type="number" name="price" required min="0" step="0.01" class="w-full border rounded px-3 py-2 outline-none">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-luxury-gold text-luxury-charcoal font-bold py-2.5 rounded uppercase tracking-wider text-[10px] hover:bg-yellow-500 transition mt-2">
                        Generate PO
                    </button>
                </form>
            </div>
        </div>

        <!-- Lists of Vendors and Purchase Orders -->
        <div class="col-lg-8 space-y-6">
            <!-- Vendors Directory -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-3">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-person-lines-fill mr-2"></i> Vendors Directory</h3>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-xs">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2">Vendor Name</th>
                                <th class="py-2">Contact Info</th>
                                <th class="py-2">GSTIN</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($vendors as $vendor)
                                <tr>
                                    <td class="py-3 font-bold text-luxury-maroon">{{ $vendor->name }}</td>
                                    <td class="py-3 text-gray-500">
                                        <span>{{ $vendor->email }}</span><br>
                                        <span>{{ $vendor->phone }}</span>
                                    </td>
                                    <td class="py-3 text-gray-500 font-mono">{{ $vendor->gstin ?? 'N/A' }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-50 text-green-700 border border-green-200">
                                            {{ $vendor->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-400">No suppliers registered.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Purchase Orders Logs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-3">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-clock-history mr-2"></i> Purchase Orders History</h3>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-xs">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2">PO Number</th>
                                <th class="py-2">Supplier</th>
                                <th class="py-2">Total Price</th>
                                <th class="py-2">Issued Date</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pos as $po)
                                <tr>
                                    <td class="py-3 font-bold text-luxury-gold font-mono">{{ $po->po_number }}</td>
                                    <td class="py-3 text-gray-500">{{ $po->vendor->name }}</td>
                                    <td class="py-3 font-bold">₹{{ number_format($po->total_amount, 2) }}</td>
                                    <td class="py-3 text-gray-500">{{ $po->created_at->format('d M Y') }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200 uppercase">
                                            {{ $po->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-400">No purchase orders issued yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
