<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductManifests extends Model
{
    use HasFactory;
    protected $table = "product_manifest";
    protected $fillable = [
        "product_id",
        "images"
    ];
}
