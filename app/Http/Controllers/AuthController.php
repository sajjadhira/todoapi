<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    // register user
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8|max:16|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]
        );

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    // login user
    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // check for email
        $user = User::where('email',$fields['email'])->first();

        if(!$user || !password_verify($fields['password'],$user->password)){
            return response(['message'=>'Bad Credentials']);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    function info(){
        $userdata = User::where('id',Auth::user()->id)->get();
        if($userdata->count() > 0 ){
            $user = $userdata[0];
            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ];

            $response = $data;
        }else{
            $response = ['message' => 'User not found'];
        }

        return response($response);
    }

    // logout user
    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response(['message'=>'logged out'],200);
    }
}
