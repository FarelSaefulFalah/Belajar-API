<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::with('kategori', 'tag', 'user')->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar berita',
            'data' => $berita,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|unique:beritas',
            'deskripsi' => 'required',
            'foto' => 'required|image|mimes:png,jpg|max:2048',
            'id_kategori' => 'required',
            'tag' => 'required|array',
            'id_user' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $path = $request->file('foto')->store('berita');
            $berita = new Berita;
            $berita->judul = $request->judul;
            $berita->slug = $request->slug ?? Str::slug($request->judul);
            $berita->deskripsi = $request->deskripsi;
            $berita->foto = $path;
            $berita->id_kategori = $request->id_kategori;
            $berita->id_user = $request->id_user;
            $berita->save();

            $berita->tag()->attach($request->tag);
            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil disimpan',
                'data' => $berita,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        // Implement the show method
    }

    public function update(Request $request, string $id)
    {
        // Implement the update method
    }

    public function destroy(string $id)
    {
        // Implement the destroy method
    }
}
