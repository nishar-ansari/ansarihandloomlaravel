<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart');
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_sku_id' => 'required|exists:product_skus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::with('brand')->findOrFail($request->product_id);
        $sku = ProductSku::with('primaryImage')->findOrFail($request->product_sku_id);

        // Real-life scenario: the submitted SKU must actually belong to the
        // submitted product, and both must be sellable - otherwise a stale
        // page or a tampered request could add a discontinued/mismatched item.
        if ($sku->product_id !== $product->id) {
            return redirect()->back()->with('error', 'This variant no longer belongs to the selected product. Please refresh the page and try again.');
        }
        if ($product->status !== 'active' || $sku->status !== 'active') {
            return redirect()->back()->with('error', 'This item is currently unavailable.');
        }

        $cart = session()->get('cart', []);

        // Key by SKU ID to support multiple variants of the same product separately
        $key = $sku->id;

        $existingQty = isset($cart[$key]) ? $cart[$key]['quantity'] : 0;
        if ($sku->stock < $existingQty + $request->quantity) {
            $available = max(0, $sku->stock - $existingQty);
            return redirect()->back()->with('error', $available > 0
                ? "Only {$available} unit(s) of {$sku->sku_code} left in stock."
                : "{$sku->sku_code} is out of stock.");
        }

        // Show the exact color/variant the customer chose, not a generic product photo.
        $imagePath = $sku->primaryImage ? $sku->primaryImage->image_path : 'saree_red.jpg';

        if(isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity;
        } else {
            $cart[$key] = [
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $sku->selling_price,
                "sku_code" => $sku->sku_code,
                "image" => $imagePath,
                "brand" => $product->brand ? $product->brand->name : 'Handwoven'
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item added to bag!');
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->route('cart.index')->with('success', 'Item removed from bag!');
        }
    }
}
