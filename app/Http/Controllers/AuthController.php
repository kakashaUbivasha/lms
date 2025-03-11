<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request, AuthService $service)
    {
        $data = $request->validated();
        try{
            $result = $service->login($data);
            return response()->json(['token'=>$result['token']], 200);
        }catch (\Exception $e){
            return response()->json(['error'=>$e->getMessage()], $e->getCode());
        }

    }
    public function register(RegisterRequest $request)
    {
        try{
            $data = $request->validated();
            User::create($data);
            return response()->json(['message'=>'пользователь создан'], 200);
        }catch (\Exception $exception){
            return response()->json([$exception],400);
        }

    }
    public function logout(Request $request)
    {
        try{
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Вы вышли из системы'], 200);
        }catch (\Exception $exception){
            return response()->json([$exception],400);
        }

    }
}
