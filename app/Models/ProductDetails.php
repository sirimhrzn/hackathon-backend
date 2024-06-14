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
        return $this->decode_json_field($value);
    }
}
