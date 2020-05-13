<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function register(Request $request) 
    {
        
        $username = $request->get('username');
        $password = $request->get('password');

        if($this->checkIfUserExist($username)){
            return response()->json([
                'message' => "user already exist"
            ], 500);
        } else {
            $password = bcrypt($password);
            User::create([
                'username' => $username,
                'password' => $password
            ]);
            return response()->json(true);
        }
    }

    private function checkIfUserExist($username) 
    {
        $user = User::where('username', $username)->first();
        if($user) {
            return $user;
        } else {
            return false;
        }
    }

    public function login(Request $request) 
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $user = $this->checkIfUserExist($username);

        if($user) {
            $confirmPassword = Hash::check($password, $user->password);
            return response()->json([
                'status' => $confirmPassword,
                'token' => $user->authToken
            ]);
        } else {
            return response()->json([
                'message' => "invalid credentials"
            ], 500);
        }
    }

    public function updateToken(Request $request) 
    {
        $username = $request->get('username');
        $token = $request->get('token');

        User::where('username', $username)->update([
            'authToken' => $token
        ]);
    }
}
