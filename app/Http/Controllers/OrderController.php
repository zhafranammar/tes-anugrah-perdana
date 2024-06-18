<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function fetchOrders(Request $request)
    {
        $search = $request->input('search');
        $orders = Order::when($search, function ($query, $search) {
            return $query->where('code', 'like', '%' . $search . '%')
                ->orWhere('customer_name', 'like', '%' . $search . '%')
                ->orWhere('product_name', 'like', '%' . $search . '%')
                ->orWhere('price', 'like', '%' . $search . '%')
                ->orWhere('quantity', 'like', '%' . $search . '%')
                ->orWhere('total', 'like', '%' . $search . '%');
        })->paginate(10);

        return response()->json($orders);
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $order = Order::create($request->all());
        return response()->json($order);
    }

    public function show($id)
    {
        $order = Order::find($id);
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $order->update($request->all());
        return response()->json($order);
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return response()->json(['success' => true]);
    }
}
