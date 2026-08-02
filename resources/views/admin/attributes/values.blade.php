@extends('admin.layouts.admin')

@section('title', 'Manage Attribute Option Values - Ansari Handloom')
@section('page_title', 'Manage Options for: ' . $attribute->name)

@section('content')
<div class="row g-4 max-w-4xl mx-auto">
    <!-- Breadcrumb Header -->
    <div class="col-12 flex items-center justify-between border-b pb-3 mb-2">
        <div>
            <h1 class="text-xl font-bold font-serif text-luxury-maroon">Configure Option Values</h1>
            <p class="text-xxs text-gray-500 mt-1">Attribute Type: <strong class="capitalize">{{ str_replace('_', ' ', $attribute->input_type) }}</strong> | Code: <strong class="font-mono text-luxury-gold">{{ $attribute->code }}</strong></p>
        </div>
        <a href="{{ route('admin.attributes.index') }}" class="text-xs text-luxury-gold font-semibold hover:underline no-underline">&larr; Return to Attributes</a>
    </div>

    <!-- Value creation form -->
    <div class="col-md-5">
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6 space-y-4">
            <h3 class="text-xs font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-2"><i class="bi bi-plus-circle mr-1"></i> Add Option Value</h3>
            
            <form action="{{ route('admin.attributes.values.store', $attribute->id) }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="font-semibold mb-1 block">Value Option Text *</label>
                    <input type="text" name="value" required placeholder="e.g. Yellow, Silk, Brocade, Bagh Print" class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none">
                </div>

                @if($attribute->input_type == 'color_picker')
                    <div>
                        <label class="font-semibold mb-1 block">Hex Colour Code</label>
                        <input type="color" name="color_code" value="#FFFFFF" class="w-full h-10 border border-luxury-gold/25 rounded cursor-pointer outline-none bg-white p-1">
                        <span class="text-[9px] text-gray-400 mt-1 block">Hex color code picker for visual display swatches.</span>
                    </div>
                @endif

                <button type="submit" class="w-full bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold py-2 rounded uppercase tracking-wider text-[10px] transition border border-luxury-gold/10">
                    Add Option Value
                </button>
            </form>
        </div>
    </div>

    <!-- Existing Values List -->
    <div class="col-md-7">
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6">
            <h3 class="text-xs font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-2 mb-4"><i class="bi bi-list-stars mr-1"></i> Registered Options</h3>
            
            <div class="table-responsive text-xs">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-gray-400 text-xxs uppercase">
                            <th>ID</th>
                            <th>Value Option</th>
                            @if($attribute->input_type == 'color_picker')
                                <th>Swatch Color</th>
                            @endif
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attribute->values as $val)
                            <tr>
                                <td class="font-bold text-gray-400 font-mono text-xxs">#{{ $val->id }}</td>
                                <td class="font-bold text-luxury-maroon text-sm">{{ $val->value }}</td>
                                @if($attribute->input_type == 'color_picker')
                                    <td>
                                        <div class="flex items-center space-x-2">
                                            <span class="w-5 h-5 rounded-full border border-gray-300 shadow-xxs inline-block" style="background-color: {{ $val->color_code }};"></span>
                                            <code class="text-xxs font-mono">{{ $val->color_code }}</code>
                                        </div>
                                    </td>
                                @endif
                                <td class="text-end">
                                    <form action="{{ route('admin.attributes.values.delete', $val->id) }}" method="POST" onsubmit="return confirm('Remove this option value? Warning: SKUs utilizing this value will lose their mapping.');">
                                        @csrf
                                        <button type="submit" class="text-danger hover:underline font-bold text-xxs bg-transparent border-none p-0 cursor-pointer">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-400 text-xxs">No option values configured for this attribute yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
