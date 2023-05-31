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
        $data=Texnika::with('organization')
        ->where(function($query) use ($request){
            $query->orwhereHas('organization',function($qe)use ($request){
                $qe->where('name','like','%'.$request->filter.'%');
            })->orwhere('nomer','like','%'.$request->filter.'%');
        })
        ->orderBy($request->sortBy,$sort)
        ->paginate($request->rowsPerPage);
        return response()->json([
            'texnikalar'=> $data,
        ]); 
    }
    public function get()
    {
        $data=Texnika::all();
        return response()->json([
            'texnikalar'=> $data,
        ]); 
    }
    public function add(Request $request)
    {

        $this->validate($request, [
            "nomer" => "required",
            "type" => "required",
            "organization_id" => "required",
        ]);

        $texnikalar = Texnika::create([
            "nomer" => $request->nomer,
            "type" => $request->type,
            "organization_id" => $request->organization_id,
        ]);
        return response()->json([
            'texnikalar'=> $texnikalar
        ]); 
    }
    public function edit(Request $request)
    {
        $this->validate($request, [
            "nomer" => "required",
            "organization_id" => "required",
            "type" => "required",
        ]);

        

        
        $texnika = Texnika::where('id',$request->id)->update([
            "organization_id" => $request->organization_id,
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
