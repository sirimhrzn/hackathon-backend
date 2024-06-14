<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permissions extends BaseModel
{
    use HasFactory;
    protected $table = "permissions";
    protected $fillable = [
        "name",
        "vendor_id",
        "slug"
    ];
}
