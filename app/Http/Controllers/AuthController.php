<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        //if validation fail
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find user by email from request "email"
        $user = User::where('email', $request->email)
            ->select('users.id', 'users.name', 'users.email', 'users.phone', 'users.password', 'users.role')
            ->first();

        // return response()->json($user);

        //if password from user and password from request not same
        if (!$user || !Hash::check($request->password, $user->password)) {
            return 'Login Gagal!';
        }

        $user->token = $user->createToken('authToken')->plainTextToken;

        //user success login and create token
        return $user;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|unique:users',
            'password' => 'required|confirmed',
            'phone'    => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create user
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'role'      => 'customer'
        ]);

        if ($user) {
            return "Register Berhasil, Silahkan Login dengan email dan password !";
        }
        return 'Data User Gagal Disimpan!';
    }

    public function logout()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $user->tokens()->delete();
        return response()->json([
            // 'isi' => $user,
            'message' => 'logout success'
        ]);
    }

    public function unauthenticate()
    {
        return response()->json([
            'status'  => false,
            'message' => "unauthenticate",
        ]);
    }
}