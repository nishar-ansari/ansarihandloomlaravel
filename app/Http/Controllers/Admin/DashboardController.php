<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total sales from completed orders
        $totalSales = Order::where('order_status', 'completed')->sum('total_amount');
        
        $totalOrders = Order::count();
        
        $totalProducts = Product::where('status', 'active')->count();
        
        // Low stock count (SKUs with less than 10 items)
        $lowStockCount = ProductSku::where('stock', '<', 10)->count();

        // Recent orders
        $recentOrders = Order::with('customer')->latest()->take(5)->get();

        // Low stock SKUs
        $lowStockItems = ProductSku::with('product')->where('stock', '<', 10)->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalProducts',
            'lowStockCount',
            'recentOrders',
            'lowStockItems'
        ));
    }
}
