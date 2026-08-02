<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout');
    }

    public function place(Request $request)
    {
        $cart = session()->get('cart');
        if (!$cart || count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Your shopping bag is empty.');
        }

        // Validate basic fields
        $rules = [
            'shipping_address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'pincode' => 'required|string',
        ];

        // If customer is guest, require details
        if (!Auth::guard('customer')->check()) {
            $rules['customer_name'] = 'required|string|max:150';
            $rules['customer_email'] = 'required|email|max:150';
            $rules['customer_phone'] = 'required|string|max:20';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            // Find or create customer
            if (Auth::guard('customer')->check()) {
                $customer = Auth::guard('customer')->user();
            } else {
                $customer = Customer::where('email', $request->customer_email)->first();
                if (!$customer) {
                    $customer = Customer::create([
                        'name' => $request->customer_name,
                        'email' => $request->customer_email,
                        'phone' => $request->customer_phone,
                        'password' => bcrypt('password'), // default dummy password for guest checkout register
                        'status' => 'active',
                    ]);
                }
            }

            // Calculations
            $subtotal = 0;
            foreach ($cart as $details) {
                $subtotal += $details['price'] * $details['quantity'];
            }
            $gst = $subtotal * 0.05;
            $shipping = $subtotal > 2000 ? 0 : 150;
            $grandTotal = $subtotal + $gst + $shipping;

            $fullAddress = $request->shipping_address . ", " . $request->city . ", " . $request->state . " - " . $request->pincode;

            // Create Order
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $grandTotal,
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'shipping_address' => $fullAddress,
            ]);

            // Save Order Items & Adjust Stock
            foreach ($cart as $skuId => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_sku_id' => $skuId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);

                // Decrement stock
                $sku = ProductSku::lockForUpdate()->find($skuId);
                if ($sku) {
                    $sku->stock = max(0, $sku->stock - $details['quantity']);
                    $sku->save();
                }
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('home')->with('success', 'Thank you! Your order ' . $order->order_number . ' has been placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error placing order: ' . $e->getMessage())->withInput();
        }
    }
}
