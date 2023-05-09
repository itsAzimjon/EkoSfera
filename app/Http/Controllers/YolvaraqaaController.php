<?php

namespace App\Http\Controllers;

use App\Yolvaraqaa;
use Illuminate\Http\Request;

class YolvaraqaaController extends Controller
{
    public function index(Request $request)
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
        $data=Yolvaraqaa::
        with('tuman')
        ->with('korxona')
        ->with('texnika')
        ->with('haydovchi')
        ->where('type',$request->type)
        ->where(function($query) use ($request){
            $query->orwhere('sana','like','%'.$request->filter.'%')
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
        ->orderBy($request->sortBy,$sort)
        ->paginate($request->rowsPerPage);
        return response()->json([
            'yolvaraqalar'=> $data,
        ]); 
    }
    public function add(Request $request)
    {

        $this->validate($request, [
            "sana" => "required",
            "tuman_id" => "required",
            "organization_id" => "required",
            "haydovchi_id" => "required",
            "texnika_id" => "required",
            "start_km" => "required",
            "finish_km" => "required",
            "yoqilgi" => "required",
            "miqdori" => "required",
            "tibbiy" => "required",
            "work_time" => "required",
            "free_time" => "required",
            "texnik" => "required",
        ]);

        $data = Yolvaraqaa::create([
            "sana" => $request->sana,
            "tuman_id" => $request->tuman_id,
            "organization_id" => $request->organization_id,
            "haydovchi_id" => $request->haydovchi_id,
            "texnika_id" => $request->texnika_id,
            "start_km" => $request->start_km,
            "finish_km" => $request->finish_km,
            "yoqilgi" => $request->yoqilgi,
            "miqdori" => $request->miqdori,
            "tibbiy" => $request->tibbiy,
            "work_time" => $request->work_time,
            "free_time" => $request->free_time,
            "texnik" => $request->texnik,
        ]);
        return response()->json([
            'yolvaraqaalar'=> $data
        ]); 
    }
    public function get()
    {
        $data=Yolvaraqaa::where('type',0)->get();
        return response()->json([
            'yolvaraqa'=> $data,
        ]); 
    }
    public function edit(Request $request)
    {
        $this->validate($request, [
            "name" => "required",
            "guvohnoma" => "required",
        ]);

        

        
        $talon = Yolvaraqaa::where('id',$request->id)->update([
            "name" => $request->name,
            "guvohnoma" => $request->guvohnoma,
        ]);
        return response()->json([
            'talon'=> $talon
        ]); 
    }
    public function delete(Request $request)
    {
        if(Yolvaraqaa::find($request->id)->delete()){
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
        

        
        $data = Yolvaraqaa::where('id',$request->id)->update([
            "type" =>$request->value,
        ]);
        return response()->json([
            'data'=> $data
        ]); 
    }
}
