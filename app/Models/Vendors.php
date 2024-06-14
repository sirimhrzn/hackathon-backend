<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendors extends BaseModel
{
    use HasFactory;
    protected $table = "vendors";
    protected $fillable = [
        "name",
        "super_user"
    ];

    public function stores()
    {
        return $this->hasMany(Stores::class);
    }
    public function categories()
    {
        return $this->hasMany(Categories::class);
    }
    public function products()
    {
        return $this->hasMany(Products::class);
    }
}
