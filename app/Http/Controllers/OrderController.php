<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function get_admin_order(OrderRequest $request){
        $orders = Orders::all();
        return response()->json([
            'data' => $orders
        ],200);
    }
    // public function update_order(OrderRequest $request){
    //     $data = $request->validated();
    //     $order_id = $data['order_id'];
    //     unset($data['order_id']);
    //     Orders::where('id',$order_id)->update($data);
    //     return response()->json([
    //         'message' => 'Product updated successfully'
    //     ],200);
    // }
}
