<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function index()
    {
        $user = User::latest()->get();
        $res = [
            "success" => true,
            "message" => "Daftar User",
            "data" => $user,
        ];
        return response()->json($res, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|unique:users",
            "password" => "required|min:8",
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
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->name;
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json(
                [
                    "success" => true,
                    "message" => "Data successfully created",
                    "data" => $user,
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
            $user = User::findOrFail($id);
            return response()->json([
                "success" => true,
                "message" => "Data retrieved successfully",
                "data" => $user
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
            "name" => "required",
            "email" => "required",
            "password" => "required|min:8",
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
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->name;
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json(
                [
                    "success" => true,
                    "message" => "Data successfully Updated",
                    "data" => $user,
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
            $user = User::findOrFail($id);
            $user->delete();
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
