<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorRequest;
use App\Models\VendorConfigs;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function create_vendor(VendorRequest $request)
    {

    }
    public function get_users(VendorRequest $request)
    {
    }
    public function create_user(VendorRequest $request)
    {
    }
    public function delete_user(VendorRequest $request)
    {
    }
    public function change_password(VendorRequest $request){
    }
    public function get_payment_options(VendorRequest $request){
        $configs = VendorConfigs::first();
        if($configs == null) {
            return response()->json([
                    'message' => 'No products avaiable'
                ]);
        }
        // $configs = $configs->toArray();
        $config = json_decode($configs['config'],true); // array with id from payment_methods and enabled
        $payment_options = [];
        foreach($config['payment_options'] as $gateway){
            if($gateway['enabled'] == 'y') {
               $payment_options[] = DB::table('payment_methods')->where('id',$gateway['id'])->first();
            }
        }
        return response()->json([
            'data' => $payment_options
        ],200);
    }
}
