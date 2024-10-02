<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'items.product'])
            ->withCount('items')
            ->get();

        $orderData = $orders->map(function ($order) {
            $totalAmount = $order->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $lastAddedToCart = CartItem::where('order_id', $order->id)
                ->orderByDesc('created_at')
                ->first()
                ->created_at ?? null;

            return [
                'order_id' => $order->id,
                'customer_name' => $order->customer->name,
                'total_amount' => $totalAmount,
                'items_count' => $order->items_count,
                'last_added_to_cart' => $lastAddedToCart,
                'completed_order_exists' => $order->status === 'completed',
                'created_at' => $order->created_at,
                'completed_at' => $order->completed_at
            ];
        });

        $sortedOrderData = $orderData->sortByDesc('completed_at')->values();

        return view('orders.index', ['orders' => $sortedOrderData]);
    }
}
