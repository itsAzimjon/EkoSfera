<?php

namespace App\Http\Controllers;

use App\Texnika;
use Illuminate\Http\Request;

class TexnikaController extends Controller
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
        $data=Texnika::
        orwhere('nomer','like','%'.$request->filter.'%')
        ->orderBy($request->sortBy,$sort)
        ->paginate($request->rowsPerPage);
        return response()->json([
            'texnikalar'=> $data,
        ]); 
    }
   
    public function add(Request $request)
    {

        $this->validate($request, [
            "nomer" => "required",
            "type" => "required",
        ]);

        $texnikalar = Texnika::create([
            "nomer" => $request->nomer,
            "type" => $request->type,
        ]);
        return response()->json([
            'texnikalar'=> $texnikalar
        ]); 
    }
    public function edit(Request $request)
    {
        $this->validate($request, [
            "nomer" => "required",
            "type" => "required",
        ]);

        

        
        $texnika = Texnika::where('id',$request->id)->update([
            "nomer" => $request->nomer,
            "type" => $request->type,
        ]);
        return response()->json([
            'texnika'=> $texnika
        ]); 
    }
    public function delete(Request $request)
    {
        if(Texnika::find($request->id)->delete()){
            return response()->json([
                'message'=>"success"
            ]); 
        }
        return response()->json([
            'message'=>"error"
        ],402); 
    }
}
