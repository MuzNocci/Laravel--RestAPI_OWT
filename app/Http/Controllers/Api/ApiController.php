<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class ApiController extends Controller
{


    public function register(Request $request){
        

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);


        return response()->json([
                'status' => true,
                'message' => 'User registered successfully',
                'data' => [],
        ]);


    }


    public function login(Request $request){


        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!empty($user)){

            if (Hash::check($request->password, $user->password)){

                $token = $user->createToken('mytoken')->plainTextToken;
                
                
                return response()->json([
                    'status' => true,
                    'message' => 'User logged in',
                    'token' => $token,
                    'data' => [],
                ]);

            }else{


                return response()->json([
                    'status' => false,
                    'message' => 'Invalid password',
                    'data' => [],
                ]);

            }

        }else{


            return response()->json([
                'status' => false,
                'message' => "Email doesn't match with records",
                'data' => [],
            ]);

        }


    }


    public function profile(){


        $userData = auth()->user();


        return response()->json([
            'status' => true,
            'message' => 'Profile information',
            'data' => $userData,
            'id' => auth()->user()->id,
        ]);


    }


    public function logout(){


        auth()->user()->tokens()->delete();


        return response()->json([
            'status' => true,
            'message' => 'User logged out',
            'data' => [],
        ]);


    }


}
