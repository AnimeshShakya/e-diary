<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affirmation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'type' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
