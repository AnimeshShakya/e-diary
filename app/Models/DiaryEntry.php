<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaryEntry extends Model
{
    protected $fillable = ['timeRange', 'timeContent', 'timeMatrix','priorities','reminder','dailyAffirmations'];
    
}
