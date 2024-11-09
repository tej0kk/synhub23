<?php

namespace App\Http\Controllers\dashboar;

use App\Helpers\Bantuan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanan = Pesanan::paginate(10);
        return $pesanan;
    }

    /**
     * Display the specified resource.
     */
    public function show(Pesanan $pesanan)
    {
        if ($pesanan) {
            return $pesanan;
        } else {
            return response()->json([
                'message' => 'Maaf, Pesanan Tidak Valid !'
            ], 422);
        }
    }

    public function ubahStatus(Pesanan $pesanan, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'    => 'required|in:1,2,3,4,5',
        ], [
            'status.required'   =>  'Tidak Valid !',
            'status.in'         =>  'Tidak Valid !',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $status = $pesanan->status;
    }

}
