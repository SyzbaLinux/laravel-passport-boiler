<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname'          => 'required|max:255',
            'lname'          => 'required|max:255',
            'email'          => 'required|email|unique:users',
            'password'       => 'required|confirmed|min:5',
            'accepted_terms' => 'required',
        ],
            [
                'fname.required'=>'First Name is required',
                'lname.required'=>'Last Name is required',
                'accepted_terms.required'=>'Please read and accept the terms and conditions',
            ]
        );
        if ($validator->fails()) {
            return response()->json(
                [
                    'errors'  => $validator->errors(),
                ],422);
        }

        if($request->accepted_terms == 0){
            return response()->json(
                [
                    'errors'  => [
                       'terms_errors' =>  ['Please Accept out terms and condtions']
                    ],
                ],422);
        }

        $user = new User();

        $user->fname    = $request->fname;
        $user->lname    = $request->lname;
        $user->email    = $request->email;
        $user->role     = 'user' ;
        $user->password = bcrypt($request->password);
        $user->mobile    = $request->mobile;
        $user->save();

        $token = $user->createToken('API Token')->accessToken;
        return response([ 'user' => $user, 'token' => $token]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($data)) {
            return response([
                'errors' => 'The email address or password you entered is incorrect'
            ],401);
        }else{

            $token = auth()->user()->createToken('API Token')->accessToken;
            return response(['user' => auth()->user(), 'token' => $token]);
        }

    }




    public function user()
    {

        if(Auth::check()){
            return response()->json([
                'user'      => Auth::user(),
            ],200);

        }else{

            return response()->json(['message'=>'Unauthorized'],401);
        }
    }

    public function logout()
    {
        Auth::user()->logout;
        return response()->json(['message' => 'Successfully logged out'],200);
    }
}
