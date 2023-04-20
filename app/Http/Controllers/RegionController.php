<?php

namespace App\Http\Controllers;

use PDF;
use App\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function get(Request $request)
    {
        $data = Region::all();
        return response()->json([
            'data' => $data,
        ], 200); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        $data = ['title' => 'Welcome to ItSolutionStuff.com'];
        $pdf = PDF::loadView('myPDF', $data);
  
        return $pdf->download('dalolatnoma.pdf');
    }

    public function viewPDF()
    {
        return view("myPDF");
    }

}
