@extends('admin.layouts.admin')

@section('title', 'Product Catalog - Ansari Handloom')
@section('page_title', 'Manage Product Catalog')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
        <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-box-seam mr-2"></i> Catalog List</h3>
        <a href="{{ route('admin.products.create') }}" class="bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold px-4 py-2 rounded-full uppercase tracking-wider text-xs transition no-underline shadow flex items-center">
            <i class="bi bi-plus-circle mr-1"></i> Add New Product
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle text-sm">
            <thead>
                <tr class="text-gray-400 text-xs uppercase">
                    <th>ID</th>
                    <th>Product details</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Attribute Set</th>
                    <th>SKUs</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td class="font-bold text-gray-400">#{{ $product->id }}</td>
                        <td>
                            <div>
                                <span class="font-bold text-luxury-maroon text-sm block">{{ $product->name }}</span>
                                <span class="text-xxs text-gray-400 block mt-0.5">Slug: {{ $product->slug }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-xs bg-luxury-beige/50 text-luxury-maroon px-2.5 py-1 rounded-full font-semibold">
                                {{ $product->category->name ?? 'None' }}
                            </span>
                        </td>
                        <td>{{ $product->brand->name ?? 'Generic' }}</td>
                        <td class="font-semibold text-luxury-gold">{{ $product->attributeSet->name ?? 'None' }}</td>
                        <td>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($product->skus as $sku)
                                    <span class="text-[10px] font-mono font-bold bg-luxury-gold/20 text-luxury-charcoal px-2 py-0.5 rounded" title="Stock: {{ $sku->stock }}">
                                        {{ $sku->sku_code }} ({{ $sku->stock }})
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-secondary rounded-full px-3 py-1 text-xs"><i class="bi bi-pencil mr-1"></i> Edit</a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-full px-3 py-1 text-xs"><i class="bi bi-trash mr-1"></i> Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
