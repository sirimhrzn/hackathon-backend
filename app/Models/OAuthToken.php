<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OAuthToken extends Model
{
    use HasFactory;
    protected $table = "oauth_tokens";
    protected $fillable = [
        "user_id",
        "access_token",
        "refresh_token",
        "revoked",
        "expiry_time",
        "provider",
        "created_at",
        "updated_at"
    ];
}
