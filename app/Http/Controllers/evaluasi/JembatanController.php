<?php

namespace App\Http\Controllers\evaluasi;

use App\Http\Controllers\Controller;
use App\Models\Jembatan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class JembatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jembatan = Jembatan::all();
        return $jembatan;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jembatan'        => 'required',
            'panjang'           => 'required',
            'tahun_pembangunan'             => 'required',
            'jenis_jembatan'  => 'required|in:rangka,gantung,beton',
        ], [
            'nama_jembatan.required' => 'Silahkan masukkan nama jembatan !',
            'panjang.required' => 'Silahkan masukkan panjang jembatan !',
            'tahun_pembangunan.required' => 'Silahkan masukkan tahun pembangunan jembatan !',
            'jenis_jembatan.required' => 'Maaf, jenis jembatan tidak valid !',
            'jenis_jembatan.in' => 'Maaf, jenis jembatan tidak valid !',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $jembatan = Jembatan::create([
            'nama_jembatan'      => $request->nama_jembatan,
            'panjang'   => $request->panjang,
            'tahun_pembangunan'     => $request->tahun_pembangunan,
            'jenis_jembatan'     => $request->jenis_jembatan
        ]);

        if ($jembatan) {
            return response()->json([
                'message' => 'Data Berhasil Disimpan',
            ], 201);
        } else {
            return response()->json([
                'message' => 'Maaf, data belum berhasil disimpan'
            ], 400);
        }
    }
}
