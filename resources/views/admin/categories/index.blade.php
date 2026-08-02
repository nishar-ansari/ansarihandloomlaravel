@extends('admin.layouts.admin')

@section('title', 'Categories Management - Ansari Handloom')
@section('page_title', 'Manage Categories')

@section('content')
<div class="row g-4">
    <!-- Category Creation Form -->
    <div class="col-md-4">
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6">
            <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-3 mb-4"><i class="bi bi-plus-circle mr-2"></i> Create Category</h3>
            
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-semibold mb-1 block">Category Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Silk Sarees" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
                </div>

                <div>
                    <label class="text-xs font-semibold mb-1 block">Parent Category</label>
                    <select name="parent_id" class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none bg-white">
                        <option value="">None (Top Level)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold mb-1 block">Sort Order</label>
                    <input type="number" name="sort_order" value="0" required class="w-full border border-luxury-gold/25 rounded px-3 py-2 text-sm outline-none">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold py-2.5 rounded-full uppercase tracking-wider text-xs transition border border-luxury-gold/10">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category List Grid -->
    <div class="col-md-8">
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6">
            <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-3 mb-4"><i class="bi bi-tags mr-2"></i> Active Categories</h3>
            
            <div class="table-responsive">
                <table class="table align-middle text-sm">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase">
                            <th>ID</th>
                            <th>Category details</th>
                            <th>Parent Category</th>
                            <th>Sort Order</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                            <tr>
                                <td class="font-bold text-gray-400">#{{ $cat->id }}</td>
                                <td>
                                    <div>
                                        <span class="font-bold text-luxury-maroon text-sm block">{{ $cat->name }}</span>
                                        <span class="text-xxs text-gray-400 block mt-0.5">Slug: {{ $cat->slug }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($cat->parent)
                                        <span class="text-xs bg-luxury-gold/15 text-luxury-maroon px-2 py-1 rounded">
                                            {{ $cat->parent->name }}
                                        </span>
                                    @else
                                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded">Top Level</span>
                                    @endif
                                </td>
                                <td>{{ $cat->sort_order }}</td>
                                <td>
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-200">
                                        {{ $cat->status }}
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
