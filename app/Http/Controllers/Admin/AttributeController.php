<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::withCount('values')->orderBy('sort_order')->get();
        return view('admin.attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|unique:attributes,code|max:50',
            'input_type' => 'required|string|in:text,textarea,number,decimal,dropdown,multiselect,checkbox,radio,switch,date,time,color_picker,file',
            'is_required' => 'nullable|boolean',
            'is_searchable' => 'nullable|boolean',
            'is_filterable' => 'nullable|boolean',
            'is_variant' => 'nullable|boolean',
            'sort_order' => 'required|integer',
        ]);

        Attribute::create([
            'name' => $request->name,
            'code' => Str::slug($request->code, '_'),
            'input_type' => $request->input_type,
            'is_required' => $request->has('is_required'),
            'is_searchable' => $request->has('is_searchable'),
            'is_filterable' => $request->has('is_filterable'),
            'is_variant' => $request->has('is_variant'),
            'sort_order' => $request->sort_order,
            'status' => 'active',
        ]);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute registered successfully!');
    }

    public function valuesIndex($id)
    {
        $attribute = Attribute::with('values')->findOrFail($id);
        return view('admin.attributes.values', compact('attribute'));
    }

    public function storeValue(Request $request, $attributeId)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:20',
        ]);

        AttributeValue::create([
            'attribute_id' => $attributeId,
            'value' => $request->value,
            'color_code' => $request->color_code,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Option value registered successfully!');
    }

    public function deleteValue($id)
    {
        $val = AttributeValue::findOrFail($id);
        $val->delete();
        return redirect()->back()->with('success', 'Option value deleted successfully!');
    }
}
