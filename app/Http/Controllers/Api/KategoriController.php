<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::latest()->get();
        $res = [
            "success" => true,
            "message" => "Daftar kategori",
            "data" => $kategori,
        ];
        return response()->json($res, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nama_kategori" => "required|unique:kategoris",
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Validation Failed",
                    "errors" => $validator->errors(),
                ],
                422
            );
        }

        try {
            $kategori = new Kategori();
            $kategori->nama_kategori = $request->nama_kategori;
            $kategori->slug = Str::slug($request->nama_kategori);
            $kategori->save();
            return response()->json(
                [
                    "success" => true,
                    "message" => "Data successfully created",
                    "data" => $kategori,
                ],
                201
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "There was a problem",
                    "errors" => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function show($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            return response()->json([
                "success" => true,
                "message" => "Data retrieved successfully",
                "data" => $kategori
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "success" => false,
                "message" => "Data not found",
                "errors" => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "There was a problem",
                "errors" => $e->getMessage(),
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "nama_kategori" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Validation Failed",
                    "errors" => $validator->errors(),
                ],
                422
            );
        }

        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->nama_kategori = $request->nama_kategori;
            $kategori->slug = Str::slug($request->nama_kategori);
            $kategori->save();
            return response()->json(
                [
                    "success" => true,
                    "message" => "Data successfully Updated",
                    "data" => $kategori,
                ],
                201
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "There was a problem",
                    "errors" => $e->getMessage(),
                ],
                500
            );
        }
     }
    public function delete($id)
    {
        try {
            $kategori = kategori::findOrFail($id);
            $kategori->delete();
            return response()->json(
                [
                    "success" => true,
                    "message" => "Data successfully deleted",
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "There was a problem",
                "errors" => $e->getMessage(),
            ], 404);
        }
    }
}
