<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegionOrganization extends Model
{
    protected $fillable = [
        "id", "region_id", "organization_id", "created_at", "updated_at"
    ];
}
