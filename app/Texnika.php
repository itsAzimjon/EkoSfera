<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Texnika extends Model
{
    protected $fillable = [
        'nomer',
        'type',
        'status',
        'organization_id'
    ];
    public function organization()
    {
        return $this->belongsTo(\App\Organization::class,'organization_id','id');
    }
}
