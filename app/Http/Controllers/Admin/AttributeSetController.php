<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeSet;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeSetController extends Controller
{
    public function index()
    {
        $attributeSets = AttributeSet::with('attributes')->latest()->get();
        $attributes = Attribute::where('status', 'active')->orderBy('sort_order')->get();
        return view('admin.attribute_sets.index', compact('attributeSets', 'attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'attributes' => 'required|array',
            'attributes.*' => 'exists:attributes,id',
        ]);

        $set = AttributeSet::create([
            'name' => $request->name,
            'status' => 'active',
        ]);

        $set->attributes()->sync($request->attributes);

        return redirect()->route('admin.attribute-sets.index')->with('success', 'Attribute Set template created successfully!');
    }
}
