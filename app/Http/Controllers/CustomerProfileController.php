<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    public function index()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login');
        }

        $customer = Auth::guard('customer')->user();
        $orders = Order::where('customer_id', $customer->id)->with('items.productSku.product')->latest()->get();

        return view('auth.profile', compact('customer', 'orders'));
    }
}
