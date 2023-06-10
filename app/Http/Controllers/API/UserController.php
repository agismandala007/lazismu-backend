<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ResponseFormatter;
use Laravel\Fortify\Rules\Password;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
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
                
            ]);

            $credentials = request(['email','password']);
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
                'cabang_id' => ['required'],
            ]);

            //create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'cabang_id' => $request->cabang_id,
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
    public function update(UpdateUserRequest $request)
    {
        try {
            $user = User::findOrFail($request->id);
            if(!$user)
            {
                throw new Exception('Coa not created');
            }
            //Update user
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'cabang_id' => $request->cabang_id,
            ]);
            if($request->has('password')) {
                $user->password = Hash::make($request->password);
            }
            
            //Generate Token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            //Return Response
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Register success');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
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

    public function fetchAll(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $role = $request->input('role');
        $cabang_id = $request->input('cabang_id');
        $limit = $request->input('limit');
        $search = $request->input('search');

        $userQuery = User::with(['cabang']);

        //get single data
        if($id)
        {
           $user = $userQuery->find($id);

            if($user)
            {
                return ResponseFormatter::success($user, "User found");
            }
            return ResponseFormatter::error('User not found');
        }

        //get multiple data
        $users = $userQuery;
       
        if($search){
            $users->where('name','like','%'.$search.'%')->orWhere('email','like','%'.$search.'%');
        }

        if($name){
            $users->where('name',$name);
        }

        if($email){
            $users->where('email',$email);
        }

        if($role){
            $users->where('role', $role);
        }
        if($cabang_id){
            $users->where('cabang_id', $cabang_id);
        }
        
        if($limit)
        {
            return ResponseFormatter::success($users->orderBy('cabang_id','asc')->paginate($limit),'Users Found');
        }

        return ResponseFormatter::success($users->get(),'Users Found');
    }

    

}
