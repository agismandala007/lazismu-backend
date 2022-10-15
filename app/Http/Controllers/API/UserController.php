<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Laravel\Fortify\Rules\Password;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'cabang_id' => 'required',
            ]);

            $credentials = request(['email','password','cabang_id']);
            if(!Auth::attempt($credentials)){
                return ResponseFormatter::error('Unauthorized', 401);
            }

            $user = User::where('email', $request->email)->first();
            if(!Hash::check($request->password, $user->password)) {
                throw new Exception('Invalid Password');
            }
            

            //Generate Token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            //return Response
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Login success');

        } catch (Exception $e) {
            return ResponseFormatter::error('Authentication Failed');
        }
        //Validasi request
    }
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required','string','max:255'],
                'email' => ['required','string','email','max:255','unique:users'],
                'password' => ['required', 'string', new Password],
                'role' => ['required'],
                'comapany_id' => ['required'],
            ]);

            //create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'company_id' => $request->company_id,
                'password' => Hash::make($request->password),
            ]);
            
            //Generate Token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            //Return Response
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Register success');
        } catch (Exception $error) {
            //error response
            return ResponseFormatter::error($error->getMessage());
        }
    }

    public function logout(Request $request)
    {
        //Revoke Token
        $token = $request->user()->currentAccessToken()->delete();

        //Return response
        return ResponseFormatter::success($token, 'Logout Success');
    }

    public function fetch(Request $request)
    {
        $user = $request->user();
        return ResponseFormatter::success($user, 'Fetch Success');
    }
}
