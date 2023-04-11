<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        "id", "name", "created_at", "updated_at"
    ];

    public function organizations(){
        return $this->belongsToMany("App\Organization", "region_organizations", "region_id", "organization_id");
    }
}