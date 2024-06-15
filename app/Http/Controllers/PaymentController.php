<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Orders;
use App\Models\PaymentMethods;
use App\Models\ProductDetails;
use App\Models\Products;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Ulid;

class PaymentController extends Controller
{
    public function handle_order(PaymentRequest $request) {
        $data = $request->validated();
        return $this->calculatePrice($data);
    }
    private function calculatePrice($payload){
        $orders= $payload['orders'];
        $total_amount = 0;
        DB::beginTransaction();
        foreach($orders as $order){
            $product = Products::where('id',$order['id'])->first(); // add validation
            $price = $product['price'];
            $total_amount += $price;
            $prod_details = ProductDetails::where('product_id',$order['id'])->first();
            $prod_details = $prod_details->toArray();
            $decoded = json_decode(json_encode($prod_details),true);
            $metadata = $decoded['metadata'];
            foreach($metadata['types'] as $index=>$type ){
                if($type['size'] == $order['type']) {
                    $reduced_count = (int)$type['stock'] - (int)$order['count'];
                    if($reduced_count < 0)
                        return response()->json([
                        'message' => 'Product of stock'
                        ],500);
                    $metadata['types'][$index]['stock'] = $reduced_count;
                }
            }
            ProductDetails::where('product_id',$order['id'])->update(['metadata' => json_encode($metadata)]);
        }
        $ulid = Ulid::generate();
        $insert_payload = [
            'id' => $ulid,
            'location_id' => $payload['location_id'],
            'payment_method_id' => $payload['payment_method'],
            'total_amount' => $total_amount,
            'order_details' => json_encode($orders),
            'payment_status' => 'Unpaid',
            'order_status' => 'Pending',
            'vendor_id' => config('request.vendor_id')
        ];
        Orders::insert($insert_payload);
        DB::commit();
        $payment_gateway = PaymentMethods::where('id',$payload['payment_method'])->get()->toArray();
        $redirect_url = $this->initiate_khalti($insert_payload);
        return response()->json([
            'redirect_url' => $redirect_url
        ],200);
    }
    private function initiate_khalti($payload) {
        $khalti_payload = [
            // 'return_url' => url()->current() . '/api/v1/global/setting/public/callback/khalti',
            'return_url' => "https://80cf-124-41-240-75.ngrok-free.app" . '/api/v1/global/setting/public/callback/khalti',
            'website_url' => "https://80cf-124-41-240-75.ngrok-free.app" . '/api/v1/global/setting/public/callback/khalti',
            'amount' => (int)$payload['total_amount'],
            'purchase_order_id' => $payload['id'],
            'purchase_order_name' => "localhost:3000"
        ];
        // dd($khalti_payload);
        $client = new Client();
        $response = $client->post('https://a.khalti.com/api/v2/epayment/initiate/',[
                     'headers' => [
                                    'Content-Type' => 'application/json',
                                    'Authorization' => 'Key a790f5b5ac634378900734cbd240767b'
                     ],
                     'json' => $khalti_payload
                ]);
        $response = json_decode($response->getBody(),true);
        $pidx = $response['pidx'];
        Orders::where('id',$payload['id'])->update([
            'payment_identifier' => $pidx
        ]);
        return $response['payment_url'];
    }
    public function handle_khalti(Request $request) {
        $pidx = $request->query('pidx');
        $status = $request->query('status');
        $tidx = $request->query('tidx');
        $redirect_url = $request->query('purchase_order_name');
        Orders::where('payment_identifier',$pidx)->update([
            'tid' => $tidx,
            'payment_status' => $status,
        ]);
        return redirect($redirect_url);
    }
}
