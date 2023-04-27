<?php

namespace App\Http\Controllers;

use App\Haydovchi;
use Illuminate\Http\Request;

class HaydovchiController extends Controller
{
    public function getAll(Request $request)
    {
        if($request->descending)
        $sort="ASC";
        else{
            $sort="desc";
        }
        if($request->sortBy=="index"){
            $request->sortBy='id';
        }
        $request->filter=strtolower( $request->filter);
        $data=Haydovchi::
        orwhere('name','like','%'.$request->filter.'%')
        ->orwhere('guvohnoma','like','%'.$request->filter.'%')
        ->orderBy($request->sortBy,$sort)
        ->paginate($request->rowsPerPage);
        return response()->json([
            'haydovchilar'=> $data,
        ]); 
    }
   
    public function add(Request $request)
    {

        $this->validate($request, [
            "name" => "required",
            "guvohnoma" => "required",
        ]);

        $haydovchilar = Haydovchi::create([
            "name" => $request->name,
            "guvohnoma" => $request->guvohnoma,
        ]);
        return response()->json([
            'haydovchilar'=> $haydovchilar
        ]); 
    }
    public function edit(Request $request)
    {
        $this->validate($request, [
            "name" => "required",
            "guvohnoma" => "required",
        ]);

        

        
        $talon = Haydovchi::where('id',$request->id)->update([
            "name" => $request->name,
            "guvohnoma" => $request->guvohnoma,
        ]);
        return response()->json([
            'talon'=> $talon
        ]); 
    }
    public function delete(Request $request)
    {
        if(Haydovchi::find($request->id)->delete()){
            return response()->json([
                'message'=>"success"
            ]); 
        }
        return response()->json([
            'message'=>"error"
        ],402); 
    }
}
