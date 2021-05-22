<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credential = Validator::make($request->all(), [
                        'email' => 'required|email',
                        'password' => 'required|string|min:8',
                    ]);

        if ($credential->fails()) {
            return response()->json(['status' => 'fail', 
                                    'message' => $credential->errors(), 
                                    'data' => null
                                    ], 422);
        }

        if (! $token = auth()->attempt($credential->validated())) {
            return response()->json(['status' => 'fail', 
                                    'message' => 'Login credentials are invalid.', 
                                    'data' => auth()->attempt($credential->validated())
                                    ], 401);
        }
        return $this->createNewToken($token);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['status' => 'success', 
                                'message' => 'User successfully signed out',
                                'data' => null
                                ], 200);
    }

    protected function createNewToken($token)
    {
        return response()->json(['status' => 'success', 
                                'message' => 'User successfully Login', 
                                'data' =>   [
                                                'access_token' => $token,
                                                'token_type' => 'bearer',
                                                'expires_in' => auth()->factory()->getTTL(),
                                                'user' => auth()->user()
                                            ]
                                ], 200);
    }
}
