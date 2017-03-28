<?php

namespace App\Http\Api\V1\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Validator;
use App\Admin;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Response;

class AdminController extends BaseController
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required | email',
            'password'=>'required'
        ]);
        if($validator->fails()){
            return ['msg'=>'缺少参数','status'=>0];
        }

        if(Admin::create([
            'name'=>$request->name,
            'password'=>bcrypt($request->password),
            'email'=>$request->email
        ])){
            return ['msg'=>'success'];
        }else{
            return ['msg'=>'faile'];
        }

    }

    public function login(Request $request){
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        try {
            $admin = Admin::first();
            $token = JWTAuth::fromUser($admin);

            // attempt to verify the credentials and create a token for the user
            //if (! $token = JWTAuth::attempt($credentials)) {
            if(!$token){
                return Response::json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return Response::json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return Response::json(compact('token'));
    }
}
