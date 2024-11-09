<?php

namespace App\Http\Controllers\dashboar;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::paginate(10);
        return $user;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|unique:users',
            'password' => 'required|confirmed|min:8',
            'phone'    => 'required|unique:users'
        ], [
            'name.required' => "Silahkan Isi Nama Lengkap Anda !!!!",
            'email.required' => "Silahkan Isi Alamat Email aktif Anda !!!!",
            'email.unique' => "Maaf Email telah terdaftar !!!!",
            'phone.unique' => "Maaf Email telah terdaftar !!!!",
            'phone.required' => "Silahkan Isi No. Telepon aktif Anda !!!!",
            'password.required' => "Silahkan Isi Password Anda !!!!",
            'password.confirmed' => "Maaf, Password yang anda masukkan tidak sama !!!!",
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
            'role'      => 'admin',
            'statu'     => 'y'
        ]);

        if ($user) {
            return response()->json([
                'message' => 'Data Berhasil Disimpan',
            ], 201);
        } else {
            return response()->json([
                'message' => 'Maaf, data belum berhasil disimpan'
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if ($user) {
            return $user;
        } else {
            return response()->json([
                'message' => 'Maaf, User Tidak Valid !'
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|unique:users',
            'password' => 'required|confirmed|min:8',
            'phone'    => 'required|unique:users'
        ], [
            'name.required' => "Silahkan Isi Nama Lengkap Anda !!!!",
            'email.required' => "Silahkan Isi Alamat Email aktif Anda !!!!",
            'email.unique' => "Maaf Email telah terdaftar !!!!",
            'phone.unique' => "Maaf Email telah terdaftar !!!!",
            'phone.required' => "Silahkan Isi No. Telepon aktif Anda !!!!",
            'password.required' => "Silahkan Isi Password Anda !!!!",
            'password.confirmed' => "Maaf, Password yang anda masukkan tidak sama !!!!",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'role'      => 'admin',
            'status'    => $request->status
        ]);

        if ($user) {
            return response()->json([
                'message' => 'Data Berhasil diupdate',
            ], 202);
        } else {
            return response()->json([
                'message' => 'Maaf, data belum berhasil diupdate'
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            return response()->json([
                'message' => 'Data Berhasil diupdate',
            ], 202);
        } else {
            return response()->json([
                'message' => 'Maaf, data belum berhasil diupdate'
            ], 422);
        }
    }

    public function ubahStatus(User $user)
    {
        if ($user->update(['status', ($user->status) == 'n' ? 'y' : 'n'])) {
            return response()->json([
                'message' => 'Data Berhasil diupdate',
            ], 202);
        } else {
            return response()->json([
                'message' => 'Maaf, data belum berhasil diupdate'
            ], 422);
        }
    }

    public function logout()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $user->tokens()->delete();

        return response()->json([
            'status' => 'berhasil',
            'message' => 'logout success'
        ]);
    }
}
