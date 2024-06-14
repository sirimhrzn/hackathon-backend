<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorUsers extends Model
{
    use HasFactory;
    protected $table = "vendor_users";
    protected $fillable = [
        "user_id",
        "vendor_id"
    ];
}
