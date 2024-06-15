<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function get_admin_orders(OrderRequest $request){
        $orders = Orders::all();
        return response()->json([
            'data' => $orders
        ],200);
    }
}
