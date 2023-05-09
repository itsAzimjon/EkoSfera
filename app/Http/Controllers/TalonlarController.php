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
        $data=Talonlar::
        with('buyurtmachi')
        ->with('buyurtma')
        ->with('yolvaraqa.haydovchi')
        ->with('yolvaraqa.texnika')
        ->orwhereHas('buyurtmachi', function($q)use ($request){
            $q->where('name','like','%'.$request->filter.'%')
            ->orwhere('stir','like','%'.$request->filter.'%');
        })
        ->orwhereHas('yolvaraqa', function($qa)use ($request){
            $qa->where('sana','like','%'.$request->filter.'%')
            ->orwhereHas('tuman', function($q)use ($request){
                $q->where('name','like','%'.$request->filter.'%');
            })
            ->orwhereHas('korxona', function($q)use ($request){
                $q->where('name','like','%'.$request->filter.'%');
            })
            ->orwhereHas('texnika', function($q)use ($request){
                $q->where('nomer','like','%'.$request->filter.'%');
            })
            ->orwhereHas('haydovchi', function($q)use ($request){
                $q->where('guvohnoma','like','%'.$request->filter.'%')
                ->orwhere('name','like','%'.$request->filter.'%');
            });
        })
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
            "buyurtmachi_id" => "required",
            "yolvaraqa_id" => "required",
            "yuk" => "required",
            "type" => "required",
           
        ]);

        $talonlar = Talonlar::create([
            "buyurtmachi_id" =>$request->buyurtmachi_id ,
            "yolvaraqa_id" =>$request->yolvaraqa_id ,
            "yuk" =>$request->yuk ,
            "olchov" =>$request->olchov ,
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
            "buyurtmachi_id" => "required",
            "yolvaraqa_id" => "required",
            "yuk" => "required",
            "type" => "required",
        ]);
        

        
        $talon = Talonlar::where('id',$request->id)->update([
            "buyurtmachi_id" =>$request->buyurtmachi_id ,
            "yolvaraqa_id" =>$request->yolvaraqa_id ,
            "yuk" =>$request->yuk ,
            "olchov" =>$request->olchov ,
            "type" =>$request->type ,
            "buyurtma_id" =>$request->buyurtma_id??0 ,
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
