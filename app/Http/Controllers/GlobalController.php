<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethods;
use Illuminate\Http\Request;

class GlobalController extends Controller
{
    public function get_payment_options(){
        $methods = PaymentMethods::where('enabled','y')->get();
        return response()->json([
            'data' => $methods
        ],200);
    }
}
