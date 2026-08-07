@extends('admin.layouts.admin')

@section('title', 'Manage Product Style - Admin')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Breadcrumb Header -->
    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <div>
            <h1 class="text-2xl font-serif font-bold text-luxury-maroon">{{ $product->name }}</h1>
            <p class="text-xs text-gray-500 mt-1">Configure global product specifications, manage variants, and map image galleries to specific options.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-xs text-luxury-gold hover:underline no-underline font-semibold">&larr; Return to Catalog</a>
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
    @if($errors->any())
        <div class="alert alert-danger text-xs rounded border border-red-300 py-2.5 px-4 bg-red-50 text-red-700">
            <strong class="block mb-1">Please fix the following:</strong>
            <ul class="mb-0 pl-4 list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Bootstrap Navigation Tabs -->
    <ul class="nav nav-tabs border-b border-luxury-gold/15 mb-6 text-xs font-semibold" id="productEditTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active text-luxury-maroon hover:text-luxury-gold py-3 px-6" id="base-info-tab" data-bs-toggle="tab" data-bs-target="#base-info" type="button" role="tab"><i class="bi bi-info-circle-fill mr-1"></i> Base Details & Specs</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-luxury-maroon hover:text-luxury-gold py-3 px-6" id="sku-variants-tab" data-bs-toggle="tab" data-bs-target="#sku-variants" type="button" role="tab"><i class="bi bi-box-seam-fill mr-1"></i> Sku Variants &amp; Photos ({{ $product->skus->count() }})</button>
        </li>
    </ul>

    <div class="tab-content" id="productEditTabsContent">
        <!-- TAB 1: BASE DETAILS & SPECS -->
        <div class="tab-pane fade show active" id="base-info" role="tabpanel">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <h3 class="text-xs uppercase font-bold text-luxury-gold font-serif tracking-wider mb-4">Base Metadata</h3>
                    <div class="row g-3 text-xs">
                        <div class="col-md-12">
                            <label class="font-semibold mb-1 block">Product Title *</label>
                            <input type="text" name="name" required value="{{ $product->name }}" class="w-full border rounded px-3 py-2 outline-none">
                        </div>

                        <div class="col-md-4">
                            <label class="font-semibold mb-1 block">Category *</label>
                            <select name="category_id" required class="w-full border rounded px-3 py-2 outline-none bg-white">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="font-semibold mb-1 block">Brand</label>
                            <select name="brand_id" class="w-full border rounded px-3 py-2 outline-none bg-white">
                                <option value="">Select Brand (Optional)</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="font-semibold mb-1 block">Listing Thumbnail</label>
                            <p class="text-[10px] text-gray-400 mt-1 mb-0 bg-gray-50 border rounded px-3 py-2">
                                Photos live on each variant now — go to the <strong>Sku Variants &amp; Photos</strong> tab, upload photos to a variant, and mark it "Default" to control the shop thumbnail.
                            </p>
                        </div>

                        <div class="col-md-12">
                            <label class="font-semibold mb-1 block">Short Description</label>
                            <input type="text" name="short_description" value="{{ $product->short_description }}" class="w-full border rounded px-3 py-2 outline-none">
                        </div>

                        <div class="col-md-12">
                            <label class="font-semibold mb-1 block">Full Description</label>
                            <textarea name="description" rows="4" class="w-full border rounded px-3 py-2 outline-none">{{ $product->description }}</textarea>
                        </div>
                    </div>

                    <!-- Dynamic Global (Non-Variant) Specifications -->
                    @php
                        $globalAttrs = $categoryAttributes->where('is_variant', 0);
                    @endphp
                    @if($globalAttrs->isNotEmpty())
                        <div class="border-t pt-6 mt-6">
                            <h3 class="text-xs uppercase font-bold text-luxury-gold font-serif tracking-wider mb-4">Global Technical Specifications</h3>
                            <div class="row g-3 text-xs">
                                @foreach($globalAttrs as $attr)
                                    @php
                                        // Retrieve saved values
                                        $savedVal = $product->globalAttributeValues->first(function($pav) use ($attr) {
                                            return $pav->attributeValue && $pav->attributeValue->attribute_id == $attr->id;
                                        });
                                        $savedText = $product->globalAttributeValues->first(function($pav) use ($attr) {
                                            return !$pav->attributeValue && $pav->text_value !== null;
                                        });
                                        $valId = $savedVal ? $savedVal->attribute_value_id : '';
                                        $textVal = $savedText ? $savedText->text_value : '';
                                    @endphp
                                    <div class="col-md-6">
                                        <label class="font-semibold mb-1 block">{{ $attr->name }} @if($attr->is_required)*@endif</label>
                                        @if($attr->input_type == 'text')
                                            <input type="text" name="global_attr[{{ $attr->id }}]" value="{{ $textVal }}" class="w-full border rounded px-3 py-2 outline-none" {{ $attr->is_required ? 'required' : '' }}>
                                        @elseif($attr->input_type == 'textarea')
                                            <textarea name="global_attr[{{ $attr->id }}]" rows="2" class="w-full border rounded px-3 py-2 outline-none" {{ $attr->is_required ? 'required' : '' }}>{{ $textVal }}</textarea>
                                        @elseif($attr->input_type == 'dropdown' || $attr->input_type == 'select' || $attr->input_type == 'radio')
                                            <select name="global_attr_val[{{ $attr->id }}]" class="w-full border rounded px-3 py-2 outline-none bg-white" {{ $attr->is_required ? 'required' : '' }}>
                                                <option value="">Select Value</option>
                                                @foreach($attr->values as $val)
                                                    <option value="{{ $val->id }}" {{ $valId == $val->id ? 'selected' : '' }}>{{ $val->value }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" name="global_attr[{{ $attr->id }}]" value="{{ $textVal }}" class="w-full border rounded px-3 py-2 outline-none" placeholder="Enter spec details...">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t flex justify-end">
                        <button type="submit" class="bg-luxury-maroon text-white font-bold px-8 py-2.5 rounded-full uppercase tracking-wider text-xs hover:bg-luxury-maroonlight transition border border-luxury-gold/10">
                            Update Base Details & Specs
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB 2: SKU VARIANTS -->
        <div class="tab-pane fade" id="sku-variants" role="tabpanel">
            <div class="row g-4">
                <!-- Sku Creation Form -->
                <div class="col-lg-4">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
                        <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-plus-circle mr-1"></i> Add Variant SKU</h3>
                        <form action="{{ route('admin.products.sku.store', $product->id) }}" method="POST" class="space-y-3 text-xs">
                            @csrf
                            <div>
                                <label class="font-semibold block mb-1">SKU Code *</label>
                                <input type="text" name="sku_code" required placeholder="e.g. BAN-SAREE-GREEN" class="w-full border rounded px-3 py-2 outline-none">
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="font-semibold block mb-1">Selling Price *</label>
                                    <input type="number" step="0.01" name="selling_price" required value="{{ old('selling_price', optional($product->skus->first())->selling_price) }}" class="w-full border rounded px-3 py-2 outline-none">
                                </div>
                                <div class="col-md-6">
                                    <label class="font-semibold block mb-1">MRP Price</label>
                                    <input type="number" step="0.01" name="mrp" class="w-full border rounded px-3 py-2 outline-none">
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="font-semibold block mb-1">Weaving Cost</label>
                                    <input type="number" step="0.01" name="cost_price" class="w-full border rounded px-3 py-2 outline-none">
                                </div>
                                <div class="col-md-6">
                                    <label class="font-semibold block mb-1">Stock Level *</label>
                                    <input type="number" name="stock" required value="10" class="w-full border rounded px-3 py-2 outline-none">
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="font-semibold block mb-1">Low Stock Alert *</label>
                                    <input type="number" name="low_stock_alert" required value="3" class="w-full border rounded px-3 py-2 outline-none">
                                </div>
                                <div class="col-md-6">
                                    <label class="font-semibold block mb-1">Weight (in grams)</label>
                                    <input type="number" step="0.01" name="weight" placeholder="e.g. 800" class="w-full border rounded px-3 py-2 outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="font-semibold block mb-1">Barcode / EAN</label>
                                <input type="text" name="barcode" class="w-full border rounded px-3 py-2 outline-none">
                            </div>

                            <!-- Dynamic Variant Attributes Selection -->
                            @php
                                $variantAttrs = $categoryAttributes->where('is_variant', 1);
                            @endphp
                            @if($variantAttrs->isNotEmpty())
                                <div class="border-t pt-3 space-y-2">
                                    <strong class="text-[10px] uppercase font-bold text-luxury-gold tracking-wider block font-serif">Configure Variant values:</strong>
                                    @foreach($variantAttrs as $attr)
                                        <div>
                                            <label class="font-semibold block mb-1">{{ $attr->name }} *</label>
                                            <select name="attributes[]" required class="w-full border rounded px-3 py-2 outline-none bg-white">
                                                <option value="">-- Select {{ $attr->name }} --</option>
                                                @foreach($attr->values as $val)
                                                    <option value="{{ $val->id }}">{{ $val->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <button type="submit" class="w-full bg-luxury-maroon text-white font-bold py-2.5 rounded uppercase tracking-wider text-[10px] hover:bg-luxury-maroonlight transition mt-2">
                                Generate Variant SKU
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Sku list grid/table -->
                <div class="col-lg-8">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
                        <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon"><i class="bi bi-box-seam-fill mr-1"></i> Current Sellable Variations</h3>
                        
                        <div class="space-y-4">
                            @foreach($product->skus as $sku)
                                <div class="border rounded-lg p-4 {{ $sku->is_default ? 'bg-luxury-beige/20 border-luxury-gold/40' : 'bg-gray-50' }} text-xs space-y-3">
                                    <div class="flex items-center justify-between border-b pb-2 mb-2">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-bold text-luxury-maroon uppercase font-mono">SKU: {{ $sku->sku_code }}</span>
                                            @if($sku->is_default)
                                                <span class="bg-luxury-maroon text-luxury-gold text-[9px] font-bold uppercase px-2 py-0.5 rounded">Default (shown in shop)</span>
                                            @else
                                                <form action="{{ route('admin.products.sku.default', $sku->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-[9px] font-bold uppercase text-luxury-gold hover:underline">Set as Default</button>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            @foreach($sku->attributeValues as $val)
                                                <span class="bg-luxury-gold/20 text-luxury-maroon px-2 py-0.5 rounded text-[9px] font-bold border border-luxury-gold/15">
                                                    {{ $val->attribute->name }}: {{ $val->value }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <form action="{{ route('admin.products.sku.update', $sku->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="sku_code" value="{{ $sku->sku_code }}">

                                        <div class="row g-2">
                                            <div class="col-md-3">
                                                <label class="font-semibold block mb-0.5">Selling Price</label>
                                                <input type="number" step="0.01" name="selling_price" value="{{ $sku->selling_price }}" required class="w-full border rounded px-2 py-1 outline-none">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="font-semibold block mb-0.5">MRP Price</label>
                                                <input type="number" step="0.01" name="mrp" value="{{ $sku->mrp }}" class="w-full border rounded px-2 py-1 outline-none">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="font-semibold block mb-0.5">Weaver Cost</label>
                                                <input type="number" step="0.01" name="cost_price" value="{{ $sku->cost_price }}" class="w-full border rounded px-2 py-1 outline-none">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="font-semibold block mb-0.5">Stock Level</label>
                                                <input type="number" name="stock" value="{{ $sku->stock }}" required class="w-full border rounded px-2 py-1 outline-none">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="font-semibold block mb-0.5">Low Stock</label>
                                                <input type="number" name="low_stock_alert" value="{{ $sku->low_stock_alert }}" required class="w-full border rounded px-2 py-1 outline-none">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="font-semibold block mb-0.5">Weight (g)</label>
                                                <input type="number" step="0.01" name="weight" value="{{ $sku->weight }}" class="w-full border rounded px-2 py-1 outline-none">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="font-semibold block mb-0.5">Barcode</label>
                                                <input type="text" name="barcode" value="{{ $sku->barcode }}" class="w-full border rounded px-2 py-1 outline-none">
                                            </div>
                                        </div>

                                        <!-- Update SKU variant values mapping dynamically -->
                                        @if($variantAttrs->isNotEmpty())
                                            <div class="pt-3 border-t mt-3 flex flex-wrap gap-3 items-center">
                                                <strong class="text-[10px] text-gray-400 uppercase tracking-wider">Configure Attributes:</strong>
                                                @foreach($variantAttrs as $attr)
                                                    @php
                                                        $currentVal = $sku->attributeValues->where('attribute_id', $attr->id)->first();
                                                    @endphp
                                                    <div class="flex items-center space-x-1">
                                                        <span class="font-semibold text-gray-500 text-[10px]">{{ $attr->name }}:</span>
                                                        <select name="attributes[]" required class="border rounded px-1 py-0.5 text-[10px] outline-none bg-white">
                                                            <option value="">Select</option>
                                                            @foreach($attr->values as $val)
                                                                <option value="{{ $val->id }}" {{ $currentVal && $currentVal->id == $val->id ? 'selected' : '' }}>{{ $val->value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="flex justify-end pt-3 border-t mt-3">
                                            <button type="submit" class="bg-luxury-gold text-luxury-charcoal font-bold px-4 py-1.5 rounded uppercase tracking-wider text-[10px] hover:bg-yellow-500 transition">
                                                Update Specs
                                            </button>
                                        </div>
                                    </form>

                                    <!-- Photos for this variant only -->
                                    <div class="pt-3 border-t mt-3 space-y-2">
                                        <div class="flex items-center justify-between flex-wrap gap-2">
                                            <strong class="text-[10px] text-gray-400 uppercase tracking-wider">Photos ({{ $sku->images->count() }})</strong>
                                            <form action="{{ route('admin.products.image.upload', $sku->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                                                @csrf
                                                <input type="file" name="product_images[]" multiple required accept=".jpg,.jpeg,.png,.gif,.webp" class="text-[10px] border rounded px-1.5 py-1 outline-none bg-white w-48">
                                                <button type="submit" class="bg-luxury-maroon text-white font-bold px-3 py-1 rounded uppercase tracking-wider text-[9px] hover:bg-luxury-maroonlight transition">
                                                    Upload
                                                </button>
                                            </form>
                                        </div>
                                        <span class="text-[9px] text-gray-400 block">JPG, PNG, GIF or WEBP - up to 8MB each, up to 10 photos at once. First photo uploaded becomes primary.</span>
                                        <div class="flex flex-wrap gap-3">
                                            @forelse($sku->images as $img)
                                                <div class="w-20 relative border rounded overflow-hidden bg-white">
                                                    <img src="{{ asset('images/' . $img->image_path) }}" class="w-full aspect-square object-cover" alt="{{ $sku->sku_code }}">
                                                    @if($img->is_primary)
                                                        <span class="absolute top-0.5 left-0.5 bg-luxury-maroon text-luxury-gold text-[7px] font-bold uppercase px-1 rounded">Primary</span>
                                                    @endif
                                                    <div class="flex border-t text-[8px]">
                                                        @if(!$img->is_primary)
                                                            <form action="{{ route('admin.products.image.primary', $img->id) }}" method="POST" class="flex-1">
                                                                @csrf
                                                                <button type="submit" class="w-full py-0.5 hover:bg-gray-100 font-semibold">Set Primary</button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('admin.products.image.delete', $img->id) }}" method="POST" class="flex-1">
                                                            @csrf
                                                            <button type="submit" onclick="return confirm('Delete this photo?')" class="w-full py-0.5 hover:bg-red-50 text-danger font-semibold">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @empty
                                                <span class="text-[10px] text-gray-400 italic">No photos yet for this variant.</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    // Re-activate whichever tab the previous form submit was on (e.g. SKU
    // create/update/upload redirect back with #sku-variants) instead of
    // always resetting to "Base Details & Specs".
    document.addEventListener('DOMContentLoaded', function () {
        if (window.location.hash) {
            var trigger = document.querySelector('button[data-bs-target="' + window.location.hash + '"]');
            if (trigger && window.bootstrap) {
                new bootstrap.Tab(trigger).show();
            }
        }
    });
</script>
@endsection
