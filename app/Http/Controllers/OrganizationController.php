<?php

namespace App\Http\Controllers;

use App\Organization;
use App\RegionOrganization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function get(Request $request)
    {
        if ($request->descending) {
            $sort = "ASC";
        } else {
            $sort = "desc";
        }  
        if ($request->sortBy == "index") {
            $request->sortBy = 'id';
        }
        $data = Organization::where('name', 'like', '%' . $request->filter . '%')->with("regions")->orderBy($request->sortBy, $sort)->paginate($request->rowsPerPage);

        return response()->json([
            'status' => 1,
            'data' => $data
        ], 200); 
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
        if (isset($request->regions_id)) {
            foreach ($request->regions_id as $region) {
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

    public function edit(Request $request)
    {
        Organization::where("id", $request->id)->update([
            "name" => $request->name
        ]);
        RegionOrganization::where("organization_id", $request->id)->delete();
        if (isset($request->regions_id)) {
            foreach ($request->regions_id as $region) {
                RegionOrganization::create([
                    "organization_id" => $request->id,
                    "region_id" => $region
                ]);
            }
        }
        return response()->json([
            "status" => 1
        ], 200);
    }
}
