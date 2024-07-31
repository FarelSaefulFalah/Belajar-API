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
        $users = User::latest()->get();
        return response()->json([
            "success" => true,
            "message" => "User list retrieved successfully",
            "data" => $users,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:8",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors(),
            ], 422);
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json([
                "success" => true,
                "message" => "User created successfully",
                "data" => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "There was a problem",
                "errors" => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                "success" => true,
                "message" => "User retrieved successfully",
                "data" => $user,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "success" => false,
                "message" => "User not found",
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
            "email" => "required|unique:users",
            "password" => "required|min:8",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors(),
            ], 422);
        }

        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json([
                "success" => true,
                "message" => "User updated successfully",
                "data" => $user,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "success" => false,
                "message" => "User not found",
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

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                "success" => true,
                "message" => "User deleted successfully",
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "success" => false,
                "message" => "User not found",
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
}
