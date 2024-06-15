<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Products extends BaseModel
{
    use HasFactory;
    protected $table = "products";
    protected $fillable = [
        "name",
        "enabled",
        "price",
        "category_id",
        "vendor_id",
        "added_by"
    ];
    protected $hidden = [
        'category_id'
    ];

    public function category()
    {
        return $this->hasMany(Categories::class, 'id', 'category_id');
    }
    public function scopeEnabled($query)
    {
        return $query->where('enabled', 'y');
    }
    public function product_details()
    {
        return $this->hasOne(ProductDetails::class, 'product_id', 'id');
    }
    public function added_by()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }
        protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->created_at ?? Carbon::now();
            $model->updated_at = $model->updated_at ?? Carbon::now();
        });
    }
}
