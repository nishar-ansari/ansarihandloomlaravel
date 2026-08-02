<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $skus = ProductSku::with('product')->latest()->get();
        return view('admin.inventory.index', compact('skus'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_sku_id' => 'required|exists:product_skus,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
        ]);

        $sku = ProductSku::findOrFail($request->product_sku_id);
        
        if ($request->type == 'in') {
            $sku->stock += $request->quantity;
        } else {
            $sku->stock = max(0, $sku->stock - $request->quantity);
        }
        
        $sku->save();

        return redirect()->route('admin.inventory.index')->with('success', 'Stock level adjusted successfully!');
    }
}
