@extends('admin.layouts.admin')

@section('title', 'Attribute Sets - Ansari Handloom')
@section('page_title', 'Manage Attribute Sets')

@section('content')
<div class="row g-4">
    <!-- Attribute Set Creation Form -->
    <div class="col-lg-4">
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6 space-y-4">
            <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-3 mb-2"><i class="bi bi-plus-circle mr-2"></i> Create Attribute Set</h3>
            
            <form action="{{ route('admin.attribute-sets.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="font-semibold mb-1 block">Set Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Saree Template, Lehenga Specs" class="w-full border border-luxury-gold/25 rounded px-3 py-2 outline-none">
                </div>

                <div>
                    <label class="font-semibold mb-2 block">Choose Mapped Attributes *</label>
                    <div class="space-y-2 bg-gray-50 border rounded p-3 text-xs max-h-60 overflow-y-auto">
                        @foreach($attributes as $attr)
                            <label class="flex items-center space-x-2 cursor-pointer pb-1.5 border-b border-gray-200/50 last:border-b-0">
                                <input type="checkbox" name="attributes[]" value="{{ $attr->id }}" class="accent-luxury-maroon">
                                <div>
                                    <span class="font-bold text-luxury-charcoal block">{{ $attr->name }}</span>
                                    <span class="text-[9px] text-gray-400 block font-mono">{{ $attr->code }} | Type: {{ $attr->input_type }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="w-full bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold py-2.5 rounded-full uppercase tracking-wider text-xs transition border border-luxury-gold/10 shadow-sm">
                    Create Attribute Set
                </button>
            </form>
        </div>
    </div>

    <!-- Attribute Sets List Grid -->
    <div class="col-lg-8">
        <div class="bg-white rounded-lg shadow-sm border border-luxury-gold/5 p-6">
            <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-3 mb-4"><i class="bi bi-collection mr-2"></i> Attribute Sets Templates</h3>
            
            <div class="table-responsive text-xs">
                <table class="table align-middle text-sm">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase">
                            <th>Template Name</th>
                            <th>Dynamic Attributes count</th>
                            <th>Mapped Attributes Details</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attributeSets as $set)
                            <tr>
                                <td>
                                    <span class="font-bold text-luxury-maroon text-sm block">{{ $set->name }}</span>
                                </td>
                                <td class="font-bold text-luxury-gold">
                                    {{ $set->attributes->count() }} attributes
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-1.5 max-w-sm">
                                        @foreach($set->attributes as $attr)
                                            <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $attr->is_variant ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                                {{ $attr->name }} ({{ $attr->is_variant ? 'Var' : 'Spec' }})
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-200">
                                        {{ $set->status }}
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
