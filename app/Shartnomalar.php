<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shartnomalar extends Model
{
    protected $fillable = [
        "id",
        "name",
        "stir",
        "type",
        "start",
        "finish",
        "manzil",
        "tuman_id",
        "raqami",
        "summa",
        "rahbar",
        "bugalter",
        "tel",
        "mfo",
        "hison_raqam",
        "qqs",
        "holati",
        "gazna",
        'gazna_name',
        "created_at", "updated_at"
    ];
    public function tuman()
    {
        return $this->belongsTo(\App\Region::class,'tuman_id','id');
    }
}
