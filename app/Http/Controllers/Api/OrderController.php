<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Services\Midtrans\CreateVaService;
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
            'bank_va_name' => 'required|string',
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
            'payment_va_name' => $request->bank_va_name,
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

        $vaService = new CreateVaService($order->load('orderItems.product', 'user'));
        $response = $vaService->getVA();

        $order->update([
            'payment_va_number' => $response->va_numbers[0]->va_number
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Order created',
            'data' => $order,
        ], 201);
    }

    public function updateShippingNumber(Request $request, $id)
    {
        $request->validate([
            'shipping_number' => 'required|string'
        ]);

        $order = Order::find($id);
        $order->update([
            'shipping_number' => $request->shipping_number
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Shipping number updated',
            'data' => $order
        ]);
    }

    public function historyOrderBuyer(Request $request)
    {
        $user = $request->user();
        $orders = Order::where('user_id', $user->id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List History order buyer',
            'data' => $orders
        ]);
    }
    public function historyOrderSeller(Request $request)
    {
        $user = $request->user();
        $orders = Order::where('seller_id', $user->id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List History order seller',
            'data' => $orders
        ]);
    }

    public function checkOrderStatus(Request $request, $id)
    {
        $order = Order::find($id);
        return response()->json([
            'status' => $order->status,
        ]);
    }

    public function getOrderById($id)
    {
        $order = Order::with('orderItems.product')->find($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Order',
            'data' => $order
        ]);
    }
}
