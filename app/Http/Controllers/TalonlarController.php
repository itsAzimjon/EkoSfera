<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Talonlar;

class TalonlarController extends Controller
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
        $data=Talonlar::where('sana','like','%'.$request->filter.'%')
        ->with('buyurtmachi')
        ->with('buyurtma')
        ->orwhereHas('buyurtmachi', function($q)use ($request){
            $q->where('name','like','%'.$request->filter.'%')
            ->orwhere('stir','like','%'.$request->filter.'%');
        })
        ->orwhere('texnika','like','%'.$request->filter.'%')
        ->orwhere('haydovchi','like','%'.$request->filter.'%')
        ->orwhere('sana','like','%'.$request->filter.'%')
        ->orwhere('yuk','like','%'.$request->filter.'%')
        ->orwhere('type','like','%'.$request->filter.'%')
        ->orderBy($request->sortBy,$sort)
        ->paginate($request->rowsPerPage);
        return response()->json([
            'talonlar'=> $data,
        ]); 
    }
   
    public function add(Request $request)
    {

        $this->validate($request, [
            "sana" => "required",
            "buyurtmachi_id" => "required",
            "texnika" => "required",
            "haydovchi" => "required",
            "yuk" => "required",
            "type" => "required",
           
        ]);

        $talonlar = Talonlar::create([
            "sana" => $request->sana,
            "buyurtmachi_id" =>$request->buyurtmachi_id ,
            "texnika" =>$request->texnika ,
            "haydovchi" =>$request->haydovchi ,
            "yuk" =>$request->yuk ,
            "type" =>$request->type ,
            "buyurtma_id" =>$request->buyurtma_id??0 ,

        ]);
        return response()->json([
            'talonlar'=> $talonlar
        ]); 
    }
    public function edit(Request $request)
    {
        $this->validate($request, [
            "sana" => "required",
            "buyurtmachi_id" => "required",
            "texnika" => "required",
            "haydovchi" => "required",
            "yuk" => "required",
            "type" => "required",
        ]);
        

        
        $talon = Talonlar::where('id',$request->id)->update([
            "sana" => $request->sana,
            "buyurtmachi_id" =>$request->buyurtmachi_id ,
            "texnika" =>$request->texnika ,
            "haydovchi" =>$request->haydovchi ,
            "yuk" =>$request->yuk ,
            "type" =>$request->type ,
        ]);
        return response()->json([
            'talon'=> $talon
        ]); 
    }
    public function delete(Request $request)
    {
        if(Talonlar::find($request->id)->delete()){
            return response()->json([
                'message'=>"success"
            ]); 
        }
        return response()->json([
            'message'=>"error"
        ],402); 
    }
}
