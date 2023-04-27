<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shartnomalar;
use App\Region;
class ShartnomalarController extends Controller
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
        $data=Shartnomalar::where('name','like','%'.$request->filter.'%')
        ->with('tuman')
        ->where('holati','!=',$request->holati)
        ->where(function($query) use ($request){
            $query->orwhere('stir','like','%'.$request->filter.'%')
            ->orwhere('rahbar','like','%'.$request->filter.'%')
            ->orwhere('bugalter','like','%'.$request->filter.'%')
            ->orwhere('tel','like','%'.$request->filter.'%')
            ->orwhere('mfo','like','%'.$request->filter.'%')
            ->orwhere('hison_raqam','like','%'.$request->filter.'%')
            ->orwhere('qqs','like','%'.$request->filter.'%');
        })
        ->orderBy($request->sortBy,$sort)
        ->paginate($request->rowsPerPage);
        return response()->json([
            'shartnomalar'=> $data,
        ]); 
    }
    public function get()
    {
        $data=Shartnomalar::all();
        return response()->json([
            'shartnomalar'=> $data,
        ]); 
    }
    public function tumanlar()
    {
        return response()->json([
            'tumanlar'=> Region::all(),
        ]);
    }
    public function add(Request $request)
    {
        $this->validate($request, [
            "name" => "required|min:3",
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
        

        
        $shartnoma = Shartnomalar::create([
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
            "gazna_name" =>$request->gazna_name??"" ,
        ]);
        return response()->json([
            'shartnoma'=> $shartnoma
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
            "gazna_name" =>$request->gazna_name??"" ,
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
    public function holat(Request $request)
    {
        $this->validate($request, [
            "id" => "required",
            'value'=>"required",
        ]);
        

        
        $shartnoma = Shartnomalar::where('id',$request->id)->update([
            "holati" =>$request->value,
        ]);
        return response()->json([
            'shartnoma'=> $shartnoma
        ]); 
    }
}
