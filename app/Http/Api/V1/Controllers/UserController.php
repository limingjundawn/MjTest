<?php

namespace App\Http\Api\V1\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\User;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Response;

class UserController extends BaseController
{
    public function test(){
        return $this->response->array(['id'=>1,'name'=>'jacket']);
    }

    /**
     * @param Request $request
     * @return array
     * 用户注册
     */
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required',
            'password'=>'required'
        ]);
        if($validator->fails()){
            return ['msg'=>'缺少参数','status'=>0];
        }
        if(User::create([
            'name'=>$request->name,
            'password'=>bcrypt($request->password),
            'email'=>$request->email
        ])){
            return ['msg'=>'success'];
        }else{
            return ['msg'=>'faile'];
        }

    }

    /**
     * @param Request $request
     * @return mixed
     * 登录接口  成功返回一个token
     */
    public function login(Request $request){
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return Response::json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return Response::json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return Response::json(compact('token'));
    }

    /**
     * @param Request $request
     * @return array
     * 退出接口，把token加入黑名单
     */
    public function logout(Request $request){
        $token = JWTAuth::getToken();
        $invalidate = JWTAuth::invalidate($token);
        if($invalidate){
            return ['msg'=>'logout success'];
        }else{
            return ['msg'=>'faile'];
        }
    }
}
