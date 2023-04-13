<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Talonlar extends Model
{
    protected $fillable = [
        "id",
        "sana",
        "buyurtmachi",
        "stir",
        "texnika",
        "haydovchi",
        "yuk",
        "chiqindi",
        "created_at", "updated_at"
    ];
}
