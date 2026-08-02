<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Models\AttributeSet;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand', 'skus', 'attributeSet'])->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        $attributeSets = AttributeSet::where('status', 'active')->get();
        return view('admin.products.create', compact('categories', 'brands', 'attributeSets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'attribute_set_id' => 'required|exists:attribute_sets,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'attribute_set_id' => $request->attribute_set_id,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'tags' => $request->tags,
            'status' => 'draft',
        ]);

        return redirect()->route('admin.products.edit', $product->id)->with('success', 'Product style created! Now configure variations, specifications and upload images.');
    }

    public function edit($id)
    {
        $product = Product::with(['skus.attributeValues', 'images.attributeValues', 'attributeSet.attributes.values'])->findOrFail($id);
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        $categoryAttributes = $product->attributeSet->attributes ?? collect();
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'categoryAttributes'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
        ]);

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'description' => $request->description,
            'short_description' => $request->short_description,
        ]);

        // Clear and rebuild global specifications (non-variant attributes)
        $product->globalAttributeValues()->delete();

        // 1. Save dropdown selection values
        if ($request->has('global_attr_val')) {
            foreach ($request->input('global_attr_val') as $attrId => $valId) {
                if (!empty($valId)) {
                    ProductAttributeValue::create([
                        'product_id' => $product->id,
                        'attribute_value_id' => $valId,
                    ]);
                }
            }
        }

        // 2. Save custom text/textarea values
        if ($request->has('global_attr')) {
            foreach ($request->input('global_attr') as $attrId => $textVal) {
                if ($textVal !== null && $textVal !== '') {
                    ProductAttributeValue::create([
                        'product_id' => $product->id,
                        'text_value' => $textVal,
                    ]);
                }
            }
        }

        // Handle cover image upload if provided
        if ($request->hasFile('primary_image')) {
            $file = $request->file('primary_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);

            // Re-map primary cover
            $product->images()->update(['is_primary' => false]);
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $filename,
                'is_primary' => true,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product style updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    public function storeSku(Request $request, $productId)
    {
        $request->validate([
            'sku_code' => 'required|string|unique:product_skus,sku_code|max:100',
            'selling_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_alert' => 'required|integer|min:0',
            'barcode' => 'nullable|string|unique:product_skus,barcode|max:100',
            'weight' => 'nullable|numeric|min:0',
            'attributes' => 'nullable|array',
            'attributes.*' => 'exists:attribute_values,id',
        ]);

        $sku = ProductSku::create([
            'product_id' => $productId,
            'sku_code' => strtoupper($request->sku_code),
            'selling_price' => $request->selling_price,
            'mrp' => $request->mrp,
            'cost_price' => $request->cost_price,
            'stock' => $request->stock,
            'low_stock_alert' => $request->low_stock_alert,
            'barcode' => $request->barcode,
            'weight' => $request->weight,
            'status' => 'active',
        ]);

        if ($request->has('attributes')) {
            $sku->attributeValues()->sync($request->input('attributes'));
        }

        return redirect()->back()->with('success', 'Variant SKU generated successfully!');
    }

    public function updateSku(Request $request, $skuId)
    {
        $sku = ProductSku::findOrFail($skuId);

        $request->validate([
            'sku_code' => 'required|string|max:100|unique:product_skus,sku_code,' . $skuId,
            'selling_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_alert' => 'required|integer|min:0',
            'barcode' => 'nullable|string|max:100|unique:product_skus,barcode,' . $skuId,
            'weight' => 'nullable|numeric|min:0',
            'attributes' => 'nullable|array',
            'attributes.*' => 'exists:attribute_values,id',
        ]);

        $sku->update([
            'sku_code' => strtoupper($request->sku_code),
            'selling_price' => $request->selling_price,
            'mrp' => $request->mrp,
            'cost_price' => $request->cost_price,
            'stock' => $request->stock,
            'low_stock_alert' => $request->low_stock_alert,
            'barcode' => $request->barcode,
            'weight' => $request->weight,
        ]);

        if ($request->has('attributes')) {
            $sku->attributeValues()->sync($request->input('attributes'));
        }

        return redirect()->back()->with('success', 'Variant SKU specs updated successfully!');
    }

    public function uploadImage(Request $request, $productId)
    {
        $request->validate([
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'attributes' => 'nullable|array',
            'attributes.*' => 'exists:attribute_values,id',
        ]);

        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);

            $img = ProductImage::create([
                'product_id' => $productId,
                'image_path' => $filename,
                'is_primary' => 0,
            ]);

            if ($request->has('attributes')) {
                $img->attributeValues()->sync($request->input('attributes'));
            }

            return redirect()->back()->with('success', 'Product photo uploaded and mapped successfully!');
        }

        return redirect()->back()->with('error', 'Error uploading photo file.');
    }

    public function deleteImage(Request $request, $imageId)
    {
        $image = ProductImage::findOrFail($imageId);
        $image->delete();

        return redirect()->back()->with('success', 'Product photo deleted successfully!');
    }

    public function updateImageColor(Request $request, $imageId)
    {
        $image = ProductImage::findOrFail($imageId);
        
        $request->validate([
            'attributes' => 'nullable|array',
            'attributes.*' => 'exists:attribute_values,id',
        ]);

        $image->attributeValues()->sync($request->input('attributes') ?: []);

        return redirect()->back()->with('success', 'Product photo option tags updated!');
    }
}
