<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $fillable = [
        "vendor_id",
        "location_id",
        "total_amount",
        "order_details",
        "payment_method_id",
        "coupon_code",
        "payment_status",
        "order_status",
        "payment_identifier",
        "tid"
    ];
    public function getOrderDetailsAttribute($value){
        return json_decode($value,true);
    }
    public function getLocationIdAttribute($value) {
        return Location::where('id',$value)->first()->name;
    }
    public function getPaymentMethodIdAttribute($value){
        return PaymentMethods::where('id',$value)->first()->name;
    }
}
