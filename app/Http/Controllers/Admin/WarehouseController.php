<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\ProductSku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with('stocks.productSku.product')->get();
        $skus = ProductSku::with('product')->get();
        return view('admin.warehouses.index', compact('warehouses', 'skus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'location' => 'nullable|string|max:255',
        ]);

        Warehouse::create($request->all());

        return redirect()->route('admin.warehouses.index')->with('success', 'Warehouse created successfully!');
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'product_sku_id' => 'required|exists:product_skus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Source stock
            $source = WarehouseStock::where('warehouse_id', $request->from_warehouse_id)
                ->where('product_sku_id', $request->product_sku_id)
                ->first();

            if (!$source || $source->stock < $request->quantity) {
                return redirect()->back()->with('error', 'Insufficient stock in source warehouse!');
            }

            // Deduct source
            $source->stock -= $request->quantity;
            $source->save();

            // Add destination
            $dest = WarehouseStock::firstOrNew([
                'warehouse_id' => $request->to_warehouse_id,
                'product_sku_id' => $request->product_sku_id
            ]);
            $dest->stock += $request->quantity;
            $dest->save();

            DB::commit();
            return redirect()->route('admin.warehouses.index')->with('success', 'Stock transferred successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error executing transfer: ' . $e->getMessage());
        }
    }
}
