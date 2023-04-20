<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
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
        $all=$this->permission($user->id);

        $response = [
            'user' => $user,
            'token' => $token,
            "permission" => $all

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
    public function permission($id)
    {
        $moduls = [
            "dashboard" => [
                "caption" => "Asosiy",
                "icon" => "dashboard",
                "url" => "dashboard",
            ],
            "organizations" => [
                "caption" => "Tashkilotlar",
                "icon" => "dashboard",
                "url" => "organizations",
            ],
            "yuridik" => [
                "label" => "Yuridik bo'lim",
                "caption" => "Shartnomalar va talonlar",
                "icon" => "assessment",
                "children" => [
                    "shartnoma" => [
                        "caption" => "Shartnomalar",
                        "icon" => "receipt",
                        "url" => "shartnomalar",
                    ],
                    "talon" => [
                        "caption" => "Talonlar",
                        "icon" => "receipt",
                        "url" => "talonlar",
                    ],
                    "dalolatnoma" => [
                        "caption" => "Dalolatnoma",
                        "icon" => "receipt",
                        "url" => "dalolatnoma",
                    ],
                    "buyurtma" =>[
                        "caption" => "Buyurtma",
                        "icon" => "receipt",
                        "url" => "buyurtma",
                    ],
                ]
            ],
            "kadr" => [
                "caption" => "Kadrlar bo'limi",
                "icon" => "people",
                "url" => "kadrlar",
            ],
            "hisobot" => [
                "caption" => "Hisobotlar",
                "icon" => "dashboard",
                "url" => "hisobotlar",
            ],
            "shablon" => [
                "caption" => "Shablonlar",
                "icon" => "dashboard",
                "url" => "shablonlar",
            ],
            "user" => [
                "caption" => "Foydalanuvchilar",
                "icon" => "people",
                "url" => "users",
            ]     
        ];
        $user = User::where("id", $id)->with("role")->first();
        $permission=json_decode($user->role->permission,TRUE)['data'];
        $all=[];
        foreach ($permission as $key => $value) {
            if (empty($moduls[$key]['label']))
            $all[]=[
                "caption" => $moduls[$key]['caption'],
                "icon" => $moduls[$key]['icon'],
                "url" => $moduls[$key]['url'],
                "read" => $value[0],
                "add" => $value[1],
                "edit" => $value[2],
                "delete" => $value[3],
            ];
            else {
                $child = [];
                foreach ($value['children'] as $k => $v) {
                    $child[] = [
                        "caption" => $moduls[$key]['children'][$k]['caption'],
                        "icon" => $moduls[$key]['children'][$k]['icon'],
                        "url" => $moduls[$key]['children'][$k]['url'],
                        "read" => $v[0],
                        "add" => $v[1],
                        "edit" => $v[2],
                        "delete" => $v[3],
                    ];
                }
                $all[] = [
                    "label" => $moduls[$key]['label'],
                    "caption" => $moduls[$key]['caption'],
                    "icon" => $moduls[$key]['icon'],
                    "read" => $value['read'],
                    'children'=>$child
                ];
            }
            
        }
        return $all;
    }
    public function checkUser(Request $request){
        
        // return Auth::user()->id;
        if(!empty(Auth::user()->id)){
            
            $user = User::where("id", Auth::user()->id)->with("role")->first();
               
            $all=$this->permission(Auth::user()->id);

            return response()->json([
                "user" => $user,
                "permission" => $all
            ], 200);
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
        $users=User::with("tuman")->with('organization')
        ->where('name','like','%'.$request->filter.'%')
        ->orwhere('email','like','%'.$request->filter.'%')
        ->orderBy($request->sortBy,$sort)
        ->paginate($request->rowsPerPage);
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
    
    public function delete(Request $request)
    {
        User::find($request->id)->delete();
        return response()->json([
            'massage'=>"Success"
        ]); 
    }
    public function organization()
    {
        $data=Organization::all();
        return response()->json([
            'organization'=>$data
        ]); 
    }
}
