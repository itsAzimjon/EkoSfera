<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buyurtma extends Model
{
    protected $fillable = [
        "id",
        "sana",
        "buyurtmachi_id",
        "masul",
        "phone",
    ];
    public function talon(){
        return $this->belongsTo("App\Talonlar",'id','buyurtma_id');
    }
    public function buyurtmachi()
    {
        return $this->belongsTo(\App\Shartnomalar::class,'buyurtmachi_id','id');
    }
}
