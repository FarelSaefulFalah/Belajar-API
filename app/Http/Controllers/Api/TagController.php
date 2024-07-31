<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\tag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TagController extends Controller
{
     public function index()
    {
        $tag = tag::latest()->get();
        $res = [
            "success" => true,
            "message" => "Daftar tag",
            "data" => $tag,
        ];
        return response()->json($res, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nama_tag" => "required|unique:tags",
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
            $tag = new tag();
            $tag->nama_tag = $request->nama_tag;
            $tag->slug = Str::slug($request->nama_tag);
            $tag->save();
            return response()->json(
                [
                    "success" => true,
                    "message" => "Data successfully created",
                    "data" => $tag,
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
            $tag = tag::findOrFail($id);
            return response()->json([
                "success" => true,
                "message" => "Data retrieved successfully",
                "data" => $tag
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
            "nama_tag" => "required",
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
            $tag = tag::findOrFail($id);
            $tag->nama_tag = $request->nama_tag;
            $tag->slug = Str::slug($request->nama_tag);
            $tag->save();
            return response()->json(
                [
                    "success" => true,
                    "message" => "Data successfully Updated",
                    "data" => $tag,
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
    public function destroy($id)
    {
        try {
            $tag = tag::findOrFail($id);
            $tag->delete();
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
