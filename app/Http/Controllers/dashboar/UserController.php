<?php

namespace App\Http\Controllers\dashboar;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;

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
            'name'      => 'required',
            'email'     => 'required',
            'phone'     => 'required',
            'password'  => 'required',
            'role'      => 'required',
            'status'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => $request->password,
            'role'      => $request->role,
            'status'    => $request->status
        ]);

        if ($user) {
            return 'Data Berhasil Disimpan';
        } else {
            return 'Maaf, data belum berhasil disimpan';
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if ($user) {
            return 'Data Berhasil Disimpan';
        } else {
            return 'Maaf, data belum berhasil disimpan';
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required',
            'phone'     => 'required',
            'password'  => 'required',
            'role'      => 'required',
            'status'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => $request->password,
            'role'      => $request->role,
            'status'    => $request->status
        ]);

        if ($user) {
            return 'Data Berhasil diupdate';
        } else {
            return 'Maaf, data belum berhasil diupdate';
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            return 'Data Berhasil Disimpan';
        } else {
            return 'Maaf, data belum berhasil dihapus';
        }
    }

    public function ubahStatus(User $user)
    {
        $user->update(['status', ($user->status) == 'n' ? 'y' : 'n']);
        return 'Status User Berhasil diubah';
    }
}
