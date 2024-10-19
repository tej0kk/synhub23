<?php

namespace App\Http\Controllers\dashboar;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banner = Banner::paginate(10);
        return $banner;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto'      => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'judul'     => 'required',
            'subjudul'  => 'required',
            'posisi'    => 'required',
            'status'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('foto');
        $image->storeAs('public/banner', $image->hashName());

        //create Slider
        $banner = Banner::create([
            'foto'       => $image->hashName(),
            'judul'      => $request->judul,
            'subjudul'   => $request->subjudul,
            'posisi'     => $request->posisi,
            'status'     => $request->status
        ]);

        if ($banner) {
            return 'Data Berhasil Disimpan';
        } else {
            return 'Maaf, data belum berhasil disimpan';
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        // $banner = Banner::whereId($id)->first();

        if ($banner) {
            return $banner;
        } else {
            return 'Maaf, data tidak ditemukan';
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $validator = Validator::make($request->all(), [
            'foto'    => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'judul'     => 'required',
            'subjudul'  => 'required',
            'posisi'    => 'required',
            'status'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('foto')) {

            Storage::disk('local')->delete('public/banner/' . basename($banner->foto));

            $image = $request->file('foto');
            $image->storeAs('public/banner', $image->hashName());

            $banner->update([
                'foto' => $image->hashName(),
                'judul'      => $request->judul,
                'subjudul'   => $request->subjudul,
                'posisi'     => $request->posisi,
                'status'     => $request->status
            ]);
        }

        //update Kategori without image
        $banner->update([
            'judul'      => $request->judul,
            'subjudul'   => $request->subjudul,
            'posisi'     => $request->posisi,
            'status'     => $request->status
        ]);

        if ($banner) {
            return 'Data Berhasil diupdate';
        } else {
            return 'Maaf, data belum berhasil diupdate';
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        Storage::disk('local')->delete('public/banner/' . basename($banner->foto));

        if ($banner->delete()) {
            return 'Data Berhasil Disimpan';
        } else {
            return 'Maaf, data belum berhasil dihapus';
        }
    }

    public function ubahStatus(Banner $banner)
    {
        $banner->update(['status', ($banner->status) == 'n' ? 'y' : 'n']);
        return 'Status Banner Berhasil diubah';
    }
}
