<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return response()->json(['status' => 'success', 'data' => $orders]);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $order]);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processed,dropped_off,shipped,out_for_delivery,delivered,canceled',
        ]);

        $order = Order::findOrFail($id);
        $order->order_status = $request->status;
        $order->save();

        return response()->json(['status' => 'success', 'message' => 'Order status updated successfully']);
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed',
        ]);

        $order = Order::findOrFail($id);
        $order->payment_status = $request->status;
        $order->save();

        return response()->json(['status' => 'success', 'message' => 'Payment status updated successfully']);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['status' => 'success', 'message' => 'Order deleted successfully']);
    }
}
