<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Haydovchi extends Model
{
    protected $fillable = [
        'name','guvohnoma','organization_id'
    ];
    public function organization()
    {
        return $this->belongsTo(\App\Organization::class,'organization_id','id');
    }
}
