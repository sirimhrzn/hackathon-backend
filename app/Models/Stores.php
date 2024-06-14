<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stores extends BaseModel
{
    use HasFactory;
    protected $table = "stores";
    protected $fillable = [
        "name",
        "vendor_id"
    ];
}
