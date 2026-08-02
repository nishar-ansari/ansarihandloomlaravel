@extends('admin.layouts.admin')

@section('title', 'Attributes Master - Ansari Handloom')
@section('page_title', 'Manage Attributes Master')

@section('content')
<div class="row g-4">
    <!-- Attribute Creation Form -->
    <div class="col-lg-4">
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6 space-y-4">
            <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-3 mb-2"><i class="bi bi-plus-circle mr-2"></i> Register Attribute</h3>
            
            <form action="{{ route('admin.attributes.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="font-semibold mb-1 block">Attribute Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Wash Care, Sleeve Length" class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none">
                </div>

                <div>
                    <label class="font-semibold mb-1 block">Unique Code *</label>
                    <input type="text" name="code" required placeholder="e.g. wash_care, sleeve_length" class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none">
                </div>

                <div>
                    <label class="font-semibold mb-1 block">Input Type *</label>
                    <select name="input_type" required class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none bg-white">
                        <option value="dropdown">Dropdown Select Box</option>
                        <option value="text">Text Field</option>
                        <option value="textarea">Text Area</option>
                        <option value="color_picker">Colour Picker</option>
                        <option value="radio">Radio Options</option>
                        <option value="checkbox">Checkbox Selector</option>
                        <option value="number">Number Field</option>
                        <option value="decimal">Decimal Field</option>
                        <option value="date">Date Picker</option>
                    </select>
                </div>

                <div>
                    <label class="font-semibold mb-1 block">Sort Order</label>
                    <input type="number" name="sort_order" value="0" required class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none">
                </div>

                <div class="border-t pt-3 space-y-2">
                    <strong class="text-[10px] uppercase font-bold text-luxury-gold tracking-wider block font-serif">Attributes Config Rules:</strong>
                    
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_variant" value="1" class="accent-luxury-maroon">
                        <span>Used for SKU Variant generation</span>
                    </label>

                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_required" value="1" class="accent-luxury-maroon">
                        <span>Required entry</span>
                    </label>

                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_searchable" value="1" class="accent-luxury-maroon">
                        <span>Searchable globally</span>
                    </label>

                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_filterable" value="1" class="accent-luxury-maroon font-bold">
                        <span>Filterable in shop catalog</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold py-2.5 rounded-full uppercase tracking-wider text-xs transition border border-luxury-gold/10 shadow-sm">
                    Register Attribute
                </button>
            </form>
        </div>
    </div>

    <!-- Attributes list Table -->
    <div class="col-lg-8">
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6">
            <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-3 mb-4"><i class="bi bi-gear mr-2"></i> Attributes Master Directory</h3>
            
            <div class="table-responsive text-xs">
                <table class="table align-middle text-sm">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase">
                            <th>Name</th>
                            <th>Code</th>
                            <th>Input Type</th>
                            <th>Variant?</th>
                            <th>Option Values count</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attributes as $attr)
                            <tr>
                                <td>
                                    <div>
                                        <span class="font-bold text-luxury-maroon text-sm block">{{ $attr->name }}</span>
                                        @if($attr->is_required)
                                            <span class="text-[9px] bg-red-50 text-red-600 border border-red-100 rounded px-1.5 font-bold uppercase mt-1 inline-block">Required</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="font-mono font-bold text-gray-500">{{ $attr->code }}</td>
                                <td class="capitalize">{{ str_replace('_', ' ', $attr->input_type) }}</td>
                                <td>
                                    <span class="badge {{ $attr->is_variant ? 'bg-success' : 'bg-secondary' }} rounded-full text-xxs px-2 py-0.5">
                                        {{ $attr->is_variant ? 'SKU Variant' : 'Global Spec' }}
                                    </span>
                                </td>
                                <td class="font-bold text-luxury-gold">
                                    {{ $attr->values_count }} values
                                </td>
                                <td class="text-end">
                                    @if(in_array($attr->input_type, ['dropdown', 'multiselect', 'checkbox', 'radio', 'color_picker']))
                                        <a href="{{ route('admin.attributes.values', $attr->id) }}" class="btn btn-xs bg-luxury-maroon hover:bg-luxury-maroonlight text-white rounded px-2.5 py-1 text-xxs font-bold no-underline inline-block">
                                            <i class="bi bi-plus-circle mr-1"></i> Add / Edit Values
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic text-xxs">No pre-defined values</span>
                                    @endif
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
