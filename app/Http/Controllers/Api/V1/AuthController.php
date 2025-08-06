<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\Admin;
use App\Models\User;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'mobile' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile ?? null,
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // Login and generate a JWT
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8',
            'mobile' => 'required|string|min:5',
        ]);
        $credentials = $request->only('mobile', 'password');

        if (!$token = Auth::guard('admin-api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('admin-api')->factory()->getTTL() * 60,
            'admin' => Auth::guard('admin-api')->user(),
        ]);
    }

    // Get authenticated user details
    public function user()
    {
        return response()->json(auth('admin-api')->user());
    }

    // Logout the user
    public function logout()
    {
        auth('admin')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
