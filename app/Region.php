<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        "id", "name", "organization_id", "created_at", "updated_at"
    ];

    public function regions()
    {
        return $this->belongsTo("App\Region", "organization_id", "id");
    }
}
