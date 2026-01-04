<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'note',
        'activity_type',
        'distance',
        'time',
        'pace',
        'speed',
    ];

    protected $casts = [
        'distance' => 'float',
        'pace' => 'float',
        'speed' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}