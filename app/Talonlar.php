<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Talonlar extends Model
{
    protected $fillable = [
        "id",
        "sana",
        "buyurtmachi_id",
        "buyurtma_id",
        "texnika",
        "haydovchi",
        "yuk",
        "type",
        "created_at", "updated_at"
    ];
    public function buyurtmachi()
    {
        return $this->belongsTo(\App\Shartnomalar::class,'buyurtmachi_id','id');
    }
    public function buyurtma()
    {
        return $this->belongsTo(\App\Buyurtma::class,'buyurtma_id','id');
    }
}
