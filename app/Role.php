<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'id',
        'name',
        'permission',
        'created_at',
        'updated_at'
    ];
}
