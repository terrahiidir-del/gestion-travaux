<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CookieConsent extends Model
{
    protected $fillable = [
        'user_id','visitor_id','choices','policy_version','ip','consented_at',
    ];

    protected $casts = [
        'choices' => 'array',
        'consented_at' => 'datetime',
    ];
}
