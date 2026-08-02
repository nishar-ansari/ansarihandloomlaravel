@extends('admin.layouts.admin')

@section('title', 'Warehouse Management - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif font-bold text-luxury-maroon">Warehouses & Stock Movements</h1>
            <p class="text-xs text-gray-500 mt-1">Manage warehouse depots and transfer stock between centers.</p>
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
        <!-- Forms panel -->
        <div class="col-lg-4 space-y-4">
            <!-- Add Warehouse Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-house-add-fill mr-2"></i> Register Warehouse</h3>
                <form action="{{ route('admin.warehouses.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="font-semibold block mb-1">Depot Name *</label>
                        <input type="text" name="name" required class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Physical Location</label>
                        <input type="text" name="location" class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <button type="submit" class="w-full bg-luxury-maroon text-white font-bold py-2.5 rounded uppercase tracking-wider text-[10px] hover:bg-luxury-maroonlight transition">
                        Register Depot
                    </button>
                </form>
            </div>

            <!-- Inter-Warehouse Transfer Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-arrow-left-right mr-2"></i> Inter-Warehouse Transfer</h3>
                <form action="{{ route('admin.warehouses.transfer') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="font-semibold block mb-1">Source Depot (From) *</label>
                        <select name="from_warehouse_id" required class="w-full border rounded px-3 py-2 outline-none">
                            <option value="">-- Select Source --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">Destination Depot (To) *</label>
                        <select name="to_warehouse_id" required class="w-full border rounded px-3 py-2 outline-none">
                            <option value="">-- Select Destination --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
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
                    <div>
                        <label class="font-semibold block mb-1">Transfer Quantity *</label>
                        <input type="number" name="quantity" required min="1" class="w-full border rounded px-3 py-2 outline-none">
                    </div>
                    <button type="submit" class="w-full bg-luxury-gold text-luxury-charcoal font-bold py-2.5 rounded uppercase tracking-wider text-[10px] hover:bg-yellow-500 transition">
                        Execute Transfer
                    </button>
                </form>
            </div>
        </div>

        <!-- Warehouses list -->
        <div class="col-lg-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-houses-fill mr-2"></i> Active Warehouses & Stocks</h3>
                
                @forelse($warehouses as $wh)
                    <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-50 shadow-xxs">
                        <div class="bg-luxury-beige/35 border-b border-gray-200 px-4 py-3 flex items-center justify-between">
                            <div>
                                <strong class="text-xs text-luxury-maroon block font-serif">{{ $wh->name }}</strong>
                                <span class="text-[10px] text-gray-400 font-semibold"><i class="bi bi-geo-alt mr-1"></i> {{ $wh->location ?? 'No location added' }}</span>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-green-50 text-green-700 border border-green-200">
                                {{ $wh->status }}
                            </span>
                        </div>
                        <div class="p-4">
                            <h4 class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-2">Stock Balances:</h4>
                            <ul class="space-y-2 text-xs mb-0">
                                @forelse($wh->stocks as $stock)
                                    <li class="flex items-center justify-between border-b border-gray-100 pb-1.5">
                                        <span class="text-luxury-charcoal">{{ $stock->productSku->product->name ?? 'SKU' }} (<strong>{{ $stock->productSku->sku_code }}</strong>)</span>
                                        <span class="font-bold text-luxury-gold">{{ $stock->stock }} units</span>
                                    </li>
                                @empty
                                    <li class="text-center py-2 text-gray-400 text-[11px]">No inventory items stored here.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400 text-sm">No warehouses registered.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
