<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    function index(Request $request)
    {
        $user= User::where('email', strtolower($request->email??""))->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'error' => ['Parol yoki email xato']
                ], 401);
            }
        
             $token = $user->createToken('my-app-token')->plainTextToken;
        
            $response = [
                'user' => $user,
                'token' => $token
            ];
        
             return response($response, 201);
    }
    public function logout()
    {
        Auth::logout();
        return [
            'message' => 'succes'
        ];
    }
    public function checkUser(Request $request){
        // return Auth::user()->id;
        if(!empty(Auth::user()->id)){
            $user = User::where("id", Auth::user()->id)->first();
            return response()->json(["user"=>$user], 200);
        }
        return response()->json([
            'error'=> "Login bo'lmagansiz"
        ], 401); 
        
    }
    public function AllUser(Request $request)
    {
        if($request->descending)
            $sort="ASC";
        else{
            $sort="desc";
        }
        if($request->sortBy=="index"){
            $request->sortBy='id';
        }
        $users=User::where('name','like','%'.$request->filter.'%')->orwhere('email','like','%'.$request->filter.'%')->orderBy($request->sortBy,$sort)->paginate($request->rowsPerPage);
        return response()->json([
            'users'=> $users,
        ]); 
    }
    public function save(Request $request)
    {
        $this->validate($request, [
            "name" => "required|min:3",
            "email" => "bail|required|email|unique:users",
            "password" => "bail|required|min:8",
            "role_id" => "required"
        ]);
        

        $p = $request->password;
        $p_r = $request->return_password;
        if($p != $p_r){
            return response()->json([
                'msg'=> "Ikki parol bir xil emas"
            ], 422);        
        }
        $password = bcrypt($request->password);
        
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => $password,
            "role_id"  => $request->role_id,
        ]);
        return response()->json([
            'user'=> $user
        ]); 
    }
    public function edit(Request $request)
    {
        $this->validate($request, [
            "id" => "required",
            "name" => "required|min:3",
            "email" => "bail|required|email",
            "role_id" => "required"
        ]);
        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "role_id"  => $request->role_id
        ];

        $p = $request->password;
        $p_r = $request->return_password;
        if($p != "" || $p_r != ""){
            if($p != $p_r){
                return response()->json([
                    'msg'=> "Ikki parol bir xil emas"
                ], 422);        
            }
            $password = bcrypt($request->password);
            $data["password"] = $password;
        } 
        
        $user = User::find($request->id)->update($data);
        return response()->json([
            'user'=> $user
        ]); 
    }
    public function count(Request $request)
    {
        $count=User::where('name','like','%'.$request->filter.'%')->orwhere('email','like','%'.$request->filter.'%')->count();
        return response()->json([
            'count'=> $count
        ]); 
    }
    public function delete(Request $request)
    {
        User::find($request->id)->delete();
        return response()->json([
            'massage'=>"Success"
        ]); 
    }
}
