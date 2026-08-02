@extends('admin.layouts.admin')

@section('title', 'Inventory Stocking - Ansari Handloom')
@section('page_title', 'Manage Inventory & Stock')

@section('content')
<div class="row g-4">
    <!-- Stock Adjustment Panel -->
    <div class="col-md-4">
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6">
            <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-3 mb-4"><i class="bi bi-graph-up-arrow mr-2"></i> Stock Adjustment</h3>
            
            <form action="{{ route('admin.inventory.adjust') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-semibold mb-1 block">Select SKU Variant *</label>
                    <select name="product_sku_id" required class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none bg-white">
                        <option value="">Choose Variant</option>
                        @foreach($skus as $sku)
                            <option value="{{ $sku->id }}">{{ $sku->sku_code }} - {{ $sku->product->name ?? 'Product' }} (Current Stock: {{ $sku->stock }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold mb-1 block">Adjustment Type *</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="border border-luxury-gold/20 rounded p-3 flex items-center justify-center space-x-2 cursor-pointer bg-white hover:border-luxury-gold">
                            <input type="radio" name="type" value="in" checked class="accent-luxury-maroon">
                            <span class="text-xs font-bold text-luxury-maroon uppercase tracking-wider">Stock In</span>
                        </label>
                        <label class="border border-luxury-gold/20 rounded p-3 flex items-center justify-center space-x-2 cursor-pointer bg-white hover:border-luxury-gold">
                            <input type="radio" name="type" value="out" class="accent-luxury-maroon">
                            <span class="text-xs font-bold text-red-600 uppercase tracking-wider">Stock Out</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold mb-1 block">Quantity *</label>
                    <input type="number" name="quantity" min="1" required placeholder="5" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold py-2.5 rounded-full uppercase tracking-wider text-xs transition border border-luxury-gold/10">
                        Adjust Stock Level
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventory Listing Grid -->
    <div class="col-md-8">
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6">
            <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-3 mb-4"><i class="bi bi-box-seam mr-2"></i> Current Stock Levels</h3>
            
            <div class="table-responsive">
                <table class="table align-middle text-sm">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase">
                            <th>SKU Code</th>
                            <th>Product details</th>
                            <th>Unit Price</th>
                            <th>Stock Level</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($skus as $sku)
                            <tr>
                                <td class="font-mono font-bold text-luxury-maroon">{{ $sku->sku_code }}</td>
                                <td>
                                    <div>
                                        <span class="font-bold text-luxury-charcoal text-sm block">{{ $sku->product->name ?? 'Product' }}</span>
                                        <span class="text-xxs text-gray-400 block mt-0.5">Product ID: #{{ $sku->product_id }}</span>
                                    </div>
                                </td>
                                <td class="font-semibold">₹{{ number_format($sku->selling_price, 2) }}</td>
                                <td>
                                    <span class="text-xs font-bold px-3 py-1 rounded border 
                                        {{ $sku->stock >= 10 ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}
                                    ">
                                        {{ $sku->stock }} Items
                                    </span>
                                </td>
                                <td>
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-200">
                                        {{ $sku->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
