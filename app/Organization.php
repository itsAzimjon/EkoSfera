<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        "id", "name", "created_at", "updated_at"
    ];

    public function regions(){
        return $this->belongsToMany("App\Region", "region_organizations", "organization_id", "region_id");
    }
}
