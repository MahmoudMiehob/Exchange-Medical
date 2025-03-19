<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'orderable_type' => 'required|in:medicine,device',
            'orderable_id' => 'required|integer',
            'user_id' => 'required|exists:users,id',
        ]);
    
        $orderableType = $request->orderable_type === 'medicine' ? 'App\Models\Medicine' : 'App\Models\Device';
    
        $order = Order::create([
            'orderable_type' => $orderableType,
            'orderable_id' => $request->orderable_id,
            'user_id' => $request->user_id,
        ]);
    
        return response()->json(['orders' => $order], 201);
    }

    // List all orders
    public function index()
    {
        $orders = Order::with(['orderable', 'user'])->get();
        return response()->json(['orders' => $orders], 200);
    }

    // List orders for a specific user
    public function userOrders($userId)
    {
        $orders = Order::with(['orderable'])
            ->where('user_id', $userId)
            ->get();

        return response()->json($orders);
    }
}
