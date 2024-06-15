<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorConfigs extends Model
{
    use HasFactory;
    protected $table = 'vendor_configs';
    protected $fillable = [
        'vendor_id',
        'config'
    ];
}
