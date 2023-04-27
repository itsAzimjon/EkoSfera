<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Texnika extends Model
{
    protected $fillable = [
        'nomer',
        'type',
        'status'
    ];
}
