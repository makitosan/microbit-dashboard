<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MicrobitData extends Model
{
    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable = ['time', 'data'];

}