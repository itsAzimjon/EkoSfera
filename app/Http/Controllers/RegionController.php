<?php

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function get(Request $request)
    {
        $datas = Region::all();
        return response()->json([
            'datas'=> $datas,
        ]); 
    }
}
