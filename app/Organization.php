<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        "id", "name", "created_at", "updated_at"
    ];

    public function regions()
    {
        return $this->hasMany("App\Region", "organization_id", "id");
    }
}
