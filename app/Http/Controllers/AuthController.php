<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;

// use Illuminate\Support\Facades\DB;


class AuthController extends BaseController
{

public function register(Request $request){
    $this->validateRequest($request,[
        'name'=>'required|string|max:255',
        'email'=>'required|string|email|max:255|unique:users',
        'password'=>'required|string|min:6|confirmed',
        'password_confirmation'=> 'required|string|min:6',
    ]);
    try{
        // DB::beginTransaction();
        $user = User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password),
    ])  ;
    // DB::commit();
    $token = auth('api')->login($user);
    return $this->respondWithToken($token,$user);

    }catch(\Exception $e){
        // DB::rollBack();
        return response()->json([
            'success'=>false,
            'message'=>$e->getMessage(),
        ],500);
    }
}
public function login(Request $request)
    {
        $this->validateRequest($request,[
            'email'=>'required|string|email|max:255',
            'password'=>'required|string|min:6',
        ]);
        try{
        $user = User::where(['email'=>$request->email])->first() ;
        if(!$user){
            return response()->json([
                'success'=>false,
                'message'=>'User not found',
            ],404);
        }
        if(!Hash::check($request->password,$user->password)){
            return response()->json([
                'success'=>false,
                'message'=>'Invalid credentials',
            ],401);
        }
if(!$token =auth('api')->login($user)){
    return response()->json([
        'success'=>false,
        'message'=>'Unauthorized',
    ],401);
}
return $this->respondWithToken($token ,$user);
    }catch(\Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage(),
            ],500);
        }
    }

    public function getUser(Request $request)
    {
        try{
            $this->validateRequest($request,[
                'id'=>'required|integer|exists:users,id',
            ]);
            $user = auth('api')->user();
            if(!$user){
           return $this->unauthorized();
        }
            $user = User::find($request->id);
        if(is_null($user)){
            return response()->json([
                'success'=>false,
                'message'=>'User not found',
            ],404);
        }else{
            return response()->json([
                'success'=>true,
                'data'=>$user,
            ],200);
        }
        }catch(\Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage(),
            ],500);
        }

    }

    public function get_all_users()
    {
        try{
            $user = auth('api')->user();
            if(!$user){
           return $this->unauthorized();
        }
            $users = User::all();
            return response()->json([
                'success'=>true,
                'data'=>$users,
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage(),
            ],500);
        }
    }
public function update_user(Request $request)
    {
       try{
        $this->validateRequest($request,[
            'id'=>'required|integer|exists:users,id',
            'name'=>'sometimes|string|max:255',
            'email'=>'sometimes|string|email|max:255|unique:users,email,'.$request->id,
            'password'=>'sometimes|string|min:6|confirmed',
            'password_confirmation'=> 'sometimes|string|min:6',
        ]);
        $user = auth('api')->user();
        if(!$user){
           return $this->unauthorized();
        }
            $user = User::find($request->id);
        if(is_null($user)){
            return response()->json([
                'success'=>false,
                'message'=>'User not found',
            ],404);
    }else{ $user->fill($request->only(['name','email','password'])); }
        if($request->has('password')){
            $user->password= Hash::make($request->password);
        }
        $user->save();
        $user->touch();
        return response()->json([
            'success'=>true,
            'data'=>$user,
        ],200);
        }catch(\Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage(),
            ],500);
        }
    }

    public function update_password(Request $request)
    {
       try{
         $this->validateRequest($request,[
            'id'=> 'required|integer|exists:users,id',
            'current_password'=>'required|string|min:6',
            'password'=>'required|string|min:6|confirmed',
            'password_confirmation'=> 'required|string|min:6',
        ]);
        $user = auth('api')->user();
        if(!$user){
           return $this->unauthorized();
        }
            $user = User::find($request->id);
            if(is_null($user)){
                return response()->json([
                    'success'=>false,
                    'message'=>'User not found',
                ],404);
            }else{
            if(!HASH::check($request->current_password,$user->password)){
                return response()->json([
                    'success'=>false,
                    'message'=>'Current password is incorrect',
                ],401);
            }
            $user->password = HASH::make($request->password);
            $user->save();
        }
        }catch(\Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage(),
            ],500);
        }
    }
    public function delete_user(Request $request)
    {
        try{
            $this->validateRequest($request,[
                'id'=>'required|integer|exists:users,id',
            ]);
            $user =auth('api')->user();
            if(!$user){
           return $this->unauthorized();
        }
            $user = User::find($request->id);
        if(is_null($user)){
            return response()->json([
                'success'=>false,
                'message'=>'User not found',
            ],404);
        }else{
            $user->delete();
            return response()->json([
                'success'=>true,
                'message'=>'User deleted successfully',
            ],200);
        }
        }catch(\Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage(),
            ],500);
        }
    }
    public function logout()
    {
        $user = auth('api')->user();
        if(!$user){
           return $this->unauthorized();
        }
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function refresh_token()
    {
        $user = auth('api')->user();
        if(!$user){
              return $this->unauthorized();
        }
        return $this->respondWithToken(auth('api')->refresh());
    }
}
