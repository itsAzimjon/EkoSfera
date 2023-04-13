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
        $data=Talonlar::where('sana','like','%'.$request->filter.'%')
       
        ->orwhere('stir','like','%'.$request->filter.'%')
        ->orwhere('buyurtmachi','like','%'.$request->filter.'%')
        ->orwhere('texnika','like','%'.$request->filter.'%')
        ->orwhere('haydovchi','like','%'.$request->filter.'%')
        ->orwhere('yuk','like','%'.$request->filter.'%')
        ->orwhere('chiqindi','like','%'.$request->filter.'%')
        ->orderBy($request->sortBy,$sort)
        ->paginate($request->rowsPerPage);
        return response()->json([
            'talonlar'=> $data,
        ]); 
    }
   
    public function add(Request $request)
    {
        dd($request);

        $this->validate($request, [
            "sana" => "required",
            "buyurtmachi" => "required",
            "stir" => "required",
            "texnika" => "required",
            "haydovchi" => "required",
            "yuk" => "required",
            "chiqindi" => "required",
           
        ]);

        $talonlar = Talonlar::create([
            "sana" => $request->sana,
            "buyurtmachi" =>$request->buyurtmachi ,
            "stir" =>$request->stir ,
            "texnika" =>$request->texnika ,
            "haydovchi" =>$request->haydovchi ,
            "yuk" =>$request->yuk ,
            "chiqindi" =>$request->chiqindi ,

        ]);
        return response()->json([
            'talonlar'=> $talonlar
        ]); 
    }
    public function edit(Request $request)
    {
        $this->validate($request, [
            "name" => "required|min:3",
            "id" => "required",
            "stir" => "required",
            "type" => "required",
            "start" => "required",
            "finish" => "required",
            "manzil" => "required",
            "tuman_id" => "required",
            "raqami" => "required",
            "summa" => "required",
            "rahbar" => "required",
            "bugalter" => "required",
            "tel" => "required",
            "mfo" => "required",
            "hison_raqam" => "required",
            "qqs" => "required",
        ]);
        

        
        $shartnoma = Shartnomalar::where('id',$request->id)->update([
            "name" => $request->name,
            "stir" =>$request->stir ,
            "type" =>$request->type ,
            "start" =>$request->start ,
            "finish" =>$request->finish ,
            "manzil" =>$request->manzil ,
            "tuman_id" =>$request->tuman_id ,
            "raqami" =>$request->raqami ,
            "summa" =>$request->summa ,
            "rahbar" =>$request->rahbar ,
            "bugalter" =>$request->bugalter ,
            "tel" =>$request->tel ,
            "mfo" =>$request->mfo ,
            "hison_raqam" =>$request->hison_raqam ,
            "qqs" =>$request->qqs ,
            "gazna" =>$request->gazna??"" ,
        ]);
        return response()->json([
            'shartnoma'=> $shartnoma
        ]); 
    }
    public function delete(Request $request)
    {
        if(Shartnomalar::find($request->id)->delete()){
            return response()->json([
                'message'=>"success"
            ]); 
        }
        return response()->json([
            'message'=>"error"
        ],402); 
    }
}
