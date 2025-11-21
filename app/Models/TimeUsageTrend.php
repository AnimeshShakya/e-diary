<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeUsageTrend extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'sleep_min',
        'management_min',
        'utilized_min',
        'wasted_min',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
