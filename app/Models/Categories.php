<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categories extends BaseModel
{
    use HasFactory;
    protected $table = "categories";
    protected $fillable = [
        "name",
        "vendor_id",
        "parent",
        "active",
        "tags"
    ];
    public function products()
    {
        return $this->hasMany(Products::class);
    }
    public function scopeActiveCategories($query) {
        return $query->where('active','y');
    }
}
