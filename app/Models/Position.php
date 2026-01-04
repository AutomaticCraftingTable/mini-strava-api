<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude',
        'longitude',
        // Add elevation, timestamp, etc. here later
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}