<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diary extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'date',
        'yesterday_sleep_time',
        'today_wake_time',
        'text',
        'urgent_important',
        'urgent_notimportant',
        'noturgent_important',
        'noturgent_notimportant',
        'good_memo',
        'sleep',
        'management',
        'utilized',
        'wasted',
        'checklist_array',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'sleep' => 'string',
            'management' => 'string',
            'utilized' => 'string',
            'wasted' => 'string',
        ];
    }

    /**
     * Get the user that owns the diary entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
