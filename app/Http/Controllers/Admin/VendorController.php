<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::latest()->get();
        $pos = PurchaseOrder::with('vendor')->latest()->get();
        $skus = ProductSku::with('product')->get();
        return view('admin.vendors.index', compact('vendors', 'pos', 'skus'));
    }

    public function storeVendor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'contact_name' => 'nullable|string|max:150',
            'email' => 'nullable|email|unique:vendors,email',
            'phone' => 'nullable|string|unique:vendors,phone',
            'gstin' => 'nullable|string|max:15',
        ]);

        Vendor::create($request->all());

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor registered successfully!');
    }

    public function storePO(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'product_sku_id' => 'required|exists:product_skus,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $totalAmount = $request->quantity * $request->price;
        $poNumber = 'PO-' . strtoupper(uniqid());

        $po = PurchaseOrder::create([
            'vendor_id' => $request->vendor_id,
            'po_number' => $poNumber,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $po->id,
            'product_sku_id' => $request->product_sku_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return redirect()->route('admin.vendors.index')->with('success', 'Purchase Order ' . $poNumber . ' created successfully!');
    }
}
