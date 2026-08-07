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
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        return redirect()->route('admin.products.edit', $product->id)->with('success', 'Product style created! Now add at least one variant SKU (color/size) with its own price, stock and photos.');
    }

    public function edit($id)
    {
        $product = Product::with(['skus.attributeValues', 'skus.images', 'attributeSet.attributes.values'])->findOrFail($id);
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

        return redirect()->route('admin.products.index')->with('success', 'Product style updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::with('skus')->findOrFail($id);

        // Real-life scenario: a product that has ever been sold must not be
        // hard-deleted - order history references its SKUs (order_items.product_sku_id
        // is a restrict-on-delete foreign key), so this would otherwise fail
        // with a raw SQL constraint error. Deactivate instead.
        $skuIds = $product->skus->pluck('id');
        $hasOrders = $skuIds->isNotEmpty() && OrderItem::whereIn('product_sku_id', $skuIds)->exists();

        if ($hasOrders) {
            return redirect()->back()->with('error', 'This product has existing orders and cannot be deleted. Set it to "inactive" instead to hide it from the shop.');
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    public function storeSku(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return $this->redirectToSkuTab($productId, null)->withErrors($validator)->withInput();
        }

        // The first SKU created for a product automatically becomes the
        // default variant (used for the storefront listing thumbnail/price).
        $isFirstSku = $product->skus()->count() === 0;

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
            'is_default' => $isFirstSku,
        ]);

        if ($request->has('attributes')) {
            $sku->attributeValues()->sync($request->input('attributes'));
        }

        return $this->redirectToSkuTab($productId, $sku->sku_code . ' created! Now upload photos for this variant.');
    }

    public function updateSku(Request $request, $skuId)
    {
        $sku = ProductSku::findOrFail($skuId);

        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return $this->redirectToSkuTab($sku->product_id)->withErrors($validator)->withInput();
        }

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

        return $this->redirectToSkuTab($sku->product_id, 'Variant SKU specs updated successfully!');
    }

    /**
     * Mark this SKU as the one representing the product on listing pages
     * (shop grid thumbnail/price) - unsets any other default for the product.
     */
    public function setDefaultSku($skuId)
    {
        $sku = ProductSku::findOrFail($skuId);

        $sku->product->skus()->update(['is_default' => false]);
        $sku->update(['is_default' => true]);

        return $this->redirectToSkuTab($sku->product_id, $sku->sku_code . ' is now the default variant shown on listing pages.');
    }

    /**
     * Photos are capped at 8MB each here to stay safely under this server's
     * upload_max_filesize/post_max_size php.ini limits - anything larger is
     * rejected by PHP itself before Laravel ever sees it, which used to fail
     * silently. Up to 10 photos can be selected and uploaded in one request.
     */
    public function uploadImage(Request $request, $skuId)
    {
        $sku = ProductSku::findOrFail($skuId);

        $validator = Validator::make($request->all(), [
            'product_images' => 'required|array|min:1|max:10',
            'product_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:8192',
        ]);

        if ($validator->fails()) {
            return $this->redirectToSkuTab($sku->product_id)->withErrors($validator)->withInput();
        }

        $isFirstBatch = $sku->images()->count() === 0;

        foreach ($request->file('product_images') as $index => $file) {
            $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);

            ProductImage::create([
                'sku_id' => $sku->id,
                'image_path' => $filename,
                // The very first photo ever uploaded for a SKU becomes its primary/listing photo.
                'is_primary' => $isFirstBatch && $index === 0,
            ]);
        }

        $count = count($request->file('product_images'));

        return $this->redirectToSkuTab($sku->product_id, $count . ' photo(s) uploaded to ' . $sku->sku_code . '!');
    }

    public function setPrimaryImage($imageId)
    {
        $image = ProductImage::findOrFail($imageId);

        ProductImage::where('sku_id', $image->sku_id)->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return $this->redirectToSkuTab($image->sku->product_id, 'Primary photo updated for this variant.');
    }

    public function deleteImage(Request $request, $imageId)
    {
        $image = ProductImage::findOrFail($imageId);
        $wasPrimary = $image->is_primary;
        $skuId = $image->sku_id;
        $productId = $image->sku->product_id;

        $image->delete();

        // Real-life scenario: don't leave a variant with photos but none
        // flagged primary - promote the next available photo automatically.
        if ($wasPrimary) {
            $next = ProductImage::where('sku_id', $skuId)->orderBy('sort_order')->first();
            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }

        return $this->redirectToSkuTab($productId, 'Photo deleted successfully!');
    }

    /**
     * Sends the admin back to the edit page with the "Sku Variants & Photos"
     * tab already active, instead of always resetting to the first tab.
     */
    private function redirectToSkuTab($productId, ?string $message = null)
    {
        $redirect = redirect(route('admin.products.edit', $productId) . '#sku-variants');

        return $message ? $redirect->with('success', $message) : $redirect;
    }
}
