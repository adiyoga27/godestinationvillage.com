<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AuthServices;
use App\Services\UserService;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Auth;

class AuthControllerApi extends Controller
{
    use JsonResponseTrait;
    public function __construct(AuthServices $authServices, UserService $userService)
    {
        $this->userServices = $userService;
        $this->authServices = $authServices;
    }
    
    public function login(Request $request)
    {
        if ($this->authServices->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user =  $this->authServices->user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['email'] =  $user->email;
            $success['name'] =  $user->name;
            $success['phone'] =  $user->phone;
            $success['country'] =  $user->country;
            $success['address'] =  $user->address;
            $success['avatar'] =  $user->avatar;
            return $this->responseDataMessage($success);
        } 

            // return $this->createErrorMessage("username atau password salah");
        return $this->errorResponseMessage('Email dan Password Salah');


    }

    public function registration(Request $request)
    {
        $result = $this->userServices->registration($request);
        if($result == true){
            return $this->responseDataMessage($result);
        }
        return $this->responseErrorDataMessage(['error' => 'Unauthorised'], 'Email Telah Terdaftar.');
    }
}
