<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $request->validate([
            'address_id' => 'required|integer',
            'seller_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer',
            'shipping_cost' => 'required|integer',
            'shipping_service' => 'required|string',
        ]);

        $user = $request->user();

        $total_price = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $total_price += $item['quantity'] * $product->price;
        }

        $grand_total = $total_price + $request->shipping_cost;

        $order = Order::create([
            'user_id' => $user->id,
            'address_id' => $request->address_id,
            'seller_id' => $request->seller_id,
            'shipping_price' => $request->shipping_cost,
            'shipping_service' => $request->shipping_service,
            'status' => 'pending',
            'total_price' => $total_price,
            'grand_total' => $grand_total,
            'transaction_number' => 'TRX-' . time(),
        ]);

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'price' => $product->price,
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order created',
            'data' => $order,
        ], 201);
    }
}
