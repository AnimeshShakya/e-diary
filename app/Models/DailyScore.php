<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'score',
    ];

    protected $casts = [
        'date' => 'date',
        'score' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
