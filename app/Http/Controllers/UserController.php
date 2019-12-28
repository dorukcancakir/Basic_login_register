<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request){
        $user = new User();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        try{
            $user->save();
            return response()->json([
                'status' => 200,
                'message' => 'Başarıyla kayıt olundu.'
            ]);
        }catch (\Exception $error){
            abort(400, 'Kullanıcı zaten kayıtlı.');
        }
    }

    public function login(Request $request)
    {

        $user = User::where('email', $request->email)->first();

        if (Hash::check($request->password, $user->password)) {
            $user->api_token = Str::random(60);

            $user->save();
            return response()->json([
                'status' => 200,
                'id' => $user->id,
                'username' => $user->name,
                'email' => $user->email,
                'api_token' => $user->api_token,
                'message' => 'Giriş başarılı!'
            ]);
        }
        return response()->json([
            'status' => 401,
            'message' => 'Unauthenticated.'
        ]);

        /*if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            return response()->json(['success' => $success], 200);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }*/
    }

    public function user(Request $request){
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthenticated.'
            ]);
        }
        return $user;
    }
}
