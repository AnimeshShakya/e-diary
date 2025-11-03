<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffirmationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'morning_done', 'night_done',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'morning_done' => 'boolean',
            'night_done' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
