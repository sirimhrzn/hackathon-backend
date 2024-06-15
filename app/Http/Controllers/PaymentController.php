<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Products;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function initiateKhalti(PaymentRequest $request) {
        $data = $request->validated();
        $price = $this->calculatePrice($data['orders']);
    }
    private function calculatePrice($orders){
        foreach($orders as $order){
            $product = Products::where('id',$order['id'])->first(); // add validation
            $price = $product['total_amount'];
            // reduce stock in order_details in order_details
        }
    }
}
