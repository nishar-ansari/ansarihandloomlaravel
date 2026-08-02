<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Expense;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Simple aggregate sales reports
        $totalSales = Order::where('payment_status', 'paid')->sum('total_amount');
        $orderCount = Order::count();
        
        // Stock statistics
        $totalStockCount = ProductSku::sum('stock');
        $lowStockCount = ProductSku::where('stock', '<', 10)->count();

        // Expenses sum
        $totalExpenses = Expense::sum('amount');

        // GST summary (5% catalog rate)
        $gstCollected = $totalSales * 0.05;

        // Net Profit estimate
        $netProfit = $totalSales - $totalExpenses - $gstCollected;

        return view('admin.reports.index', compact(
            'totalSales',
            'orderCount',
            'totalStockCount',
            'lowStockCount',
            'totalExpenses',
            'gstCollected',
            'netProfit'
        ));
    }
}
