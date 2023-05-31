<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Yolvaraqaa extends Model
{
    protected $fillable = [
        "id",
        "sana",
        "raqami",
        "tuman_id",
        "organization_id",
        "haydovchi_id",
        "texnika_id",
        "start_km",
        "finish_km",
        'yoqilgi',
        'miqdori',
        'tibbiy',
        'texnik',
        'work_time',
        'free_time',
        'type',
        "created_at", "updated_at"
    ];
    public function korxona()
    {
        return $this->belongsTo(\App\Organization::class,'organization_id','id');
    }
    public function tuman()
    {
        return $this->belongsTo(\App\Region::class,'tuman_id','id');
    }
    public function haydovchi()
    {
        return $this->belongsTo(\App\Haydovchi::class,'haydovchi_id','id');
    }
    public function texnika()
    {
        return $this->belongsTo(\App\Texnika::class,'texnika_id','id');
    }
}
