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

        $product = Product::with('brand', 'primaryImage')->findOrFail($request->product_id);
        $sku = ProductSku::findOrFail($request->product_sku_id);

        $cart = session()->get('cart', []);

        // Key by SKU ID to support multiple variants of the same product separately
        $key = $sku->id;

        $imagePath = $product->primaryImage ? $product->primaryImage->image_path : 'saree_red.jpg';

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
