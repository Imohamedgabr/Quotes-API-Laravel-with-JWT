<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;

class UserController extends Controller
{
    public function signup(Request $request)
    {
    	$this->validate($request , [
    			'name' => 'required',
    			'email'=> 'required|email|unique:users',
    			'password'=> 'required'
    		]);

    	$user = new User([
    			'name' => $request->input('name'),
    			'email' => $request->input('email'),
    			'password' => bcrypt($request->input('password'))
    			
    		]);

    	$user->save();
    	return response()->json([
    			'message' => 'Successfully created user'
    		] ,201);
    }

    public function signin(Request $request)
    {
        $this->validate($request , [
                'email'=> 'required|email',
                'password'=> 'required'
            ]);

        $credentials = $request->only('email' , 'password');

        try{
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json( [
                            'error' => 'invalid credentials!'
                        ],401);
                }
        }catch(JWTException $e){
            return response()->json([
                    'error' => 'could not create a token'
                ] ,500);
        }

        return response()->json([
                'token' => $token
            ] ,200);
    }
}
