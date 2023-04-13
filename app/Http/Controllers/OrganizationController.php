<?php

namespace App\Http\Controllers;

use App\Organization;
use App\RegionOrganization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function get()
    {
        
    }

    public function add(Request $request)
    {
        $check = Organization::where("name", $request->name)->first();
        if (isset($check)) {
            return response()->json([
                "status" => 0,
                "error" => "Bunday nomli korxona mavjud"
            ], 422); 
        }
        $data = Organization::create([
            "name" => $request->name
        ]);
        if (isset($request->regions)) {
            foreach ($request->regions as $region) {
                RegionOrganization::create([
                    "organization_id" => $data->id,
                    "region_id" => $region
                ]);
            }
        }

        return response()->json([
            "status" => 1
        ], 201);
    }
}
