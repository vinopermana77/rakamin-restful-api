<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $tokenResult = $user->createToken('Personal Access Token');
        return response([
            'token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ], 200);
    }
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $tokenResult = $user->createToken('Personal Access Token');
            return response([
                'token' => $tokenResult->accessToken,
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ], 200);
        } else {
            return response([
                "message" => "Unauthorised."
            ], 401);
        }
    }
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
