<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductDetails extends BaseModel
{
    use HasFactory;
    protected $table = "product_details";
    protected $fillable = [
        "product_id",
        "vendor_id",
        "details",
        "metadata"
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    // public function scopeOutOfStock($query)
    // {
    //     return $query->where('stock',0);
    // }
    // public function scopeInStock($query)
    // {
    //     return $query->whereNot('stock',0);
    // }
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
    public function getDetailsAttribute($value)
    {
        return $this->decode_json_field($value);
    }
    public function getMetadataAttribute($value)
    {
       $data = $this->decode_json_field($value);
        $data = json_decode(json_encode($data),true);
        if(isset($data['images'])){
            foreach($data['images'] as $index=>$image) {
                if(!str_contains($image,'http')){
                    $data['images'][$index] = config('filesystems.disks.minio.endpoint') . "/spcms/$image";
                }
            }
        }
        return $data;
    }
}
