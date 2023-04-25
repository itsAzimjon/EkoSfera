<?php

namespace App\Http\Controllers;
use App\Buyurtma;
use Illuminate\Http\Request;

class BuyurtmaController extends Controller
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
        $data=Buyurtma::with('talon')->with('buyurtmachi')
        ->orwhereHas('buyurtmachi', function($q)use ($request){
            $q->where('name','like','%'.$request->filter.'%')
            ->orwhere('stir','like','%'.$request->filter.'%');
        })
        ->orwhere('masul','like','%'.$request->filter.'%')
        ->orwhere('phone','like','%'.$request->filter.'%')
        ->orwhere('sana','like','%'.$request->filter.'%')
        ->orderBy($request->sortBy,$sort)
        ->paginate($request->rowsPerPage);
        return response()->json([
            'buyurtma'=> $data,
        ]); 
    }
    public function get()
    {
        $buyurtmalar=Buyurtma::with('buyurtmachi')->get();
        return response()->json([
            'buyurtmalar'=> $buyurtmalar,
        ]); 
    }

    public function add(Request $request)
    {

        $this->validate($request, [
            "sana" => "required",
            "buyurtmachi_id" => "required",
            "masul" => "required",
            "phone" => "required",
           
        ]);

        $Buyurtma = Buyurtma::create([
            "sana" => $request->sana,
            "buyurtmachi_id" =>$request->buyurtmachi_id ,
            "masul" =>$request->masul ,
            "phone" =>$request->phone ,

        ]);
        return response()->json([
            'buyurtma'=> $Buyurtma
        ]); 
    }
    public function edit(Request $request)
    {
        $this->validate($request, [
            "sana" => "required",
            "buyurtmachi_id" => "required",
            "masul" => "required",
            "phone" => "required",
        ]);
        

        
        $Buyurtma = Buyurtma::where('id',$request->id)->update([
            "sana" => $request->sana,
            "buyurtmachi_id" =>$request->buyurtmachi_id ,
            "masul" =>$request->masul ,
            "phone" =>$request->phone ,

        ]);
        return response()->json([
            'buyurtma'=> $Buyurtma
        ]); 
    }
    public function delete(Request $request)
    {
        if(Buyurtma::find($request->id)->delete()){
            return response()->json([
                'message'=>"success"
            ]); 
        }
        return response()->json([
            'message'=>"error"
        ],402); 
    }
}
