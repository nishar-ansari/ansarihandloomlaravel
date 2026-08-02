@extends('admin.layouts.admin')

@section('title', 'Add Product Style - Admin')
@section('page_title', 'Create New Product Style')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-8 max-w-3xl mx-auto">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
        <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-plus-circle mr-2"></i> Add Product Details</h3>
        <a href="{{ route('admin.products.index') }}" class="text-xs text-luxury-gold font-semibold hover:underline no-underline">&larr; Return to Catalog</a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="row g-3 text-xs">
            <div class="col-md-12">
                <label class="font-semibold mb-1 block">Product Title *</label>
                <input type="text" name="name" required placeholder="e.g. Traditional Zari Border Saree" class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none">
            </div>

            <div class="col-md-4">
                <label class="font-semibold mb-1 block">Category *</label>
                <select name="category_id" required class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none bg-white">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="font-semibold mb-1 block">Brand</label>
                <select name="brand_id" class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none bg-white">
                    <option value="">Select Brand (Optional)</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="font-semibold mb-1 block">Attribute Set *</label>
                <select name="attribute_set_id" required class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none bg-white">
                    <option value="">Select Attribute Set</option>
                    @foreach($attributeSets as $set)
                        <option value="{{ $set->id }}">{{ $set->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12">
                <label class="font-semibold mb-1 block">Short Description</label>
                <input type="text" name="short_description" placeholder="Brief summary of fabric, pattern, or weavers..." class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none">
            </div>

            <div class="col-md-12">
                <label class="font-semibold mb-1 block">Full Description</label>
                <textarea name="description" rows="4" placeholder="Detailed description of weave style, material content, size, and care instructions..." class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none"></textarea>
            </div>

            <div class="col-md-6">
                <label class="font-semibold mb-1 block">Meta Title (SEO)</label>
                <input type="text" name="meta_title" placeholder="SEO Title" class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none">
            </div>

            <div class="col-md-6">
                <label class="font-semibold mb-1 block">Search Tags (Comma separated)</label>
                <input type="text" name="tags" placeholder="handloom, silk, banarasi, wedding" class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none">
            </div>

            <div class="col-md-12">
                <label class="font-semibold mb-1 block">Meta Description (SEO)</label>
                <textarea name="meta_description" rows="2" placeholder="SEO description..." class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none"></textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold px-8 py-3 rounded-full uppercase tracking-wider text-xs transition shadow-md border border-luxury-gold/10">
                Publish Product Style
            </button>
        </div>
    </form>
</div>
@endsection
