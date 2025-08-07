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

        $accessToken = JWTAuth::fromUser($user);

        $refreshToken = JWTAuth::claims(['type' => 'refresh'])
        ->fromUser($user, JWTAuth::factory()->setTTL(60 * 24 * 7)->getTTL());

        return response()->json([
            'message' => 'Registered successfully',
            'user' => $user,
            'access_token' => $accessToken,
        ])->cookie(
            'refresh_token', $refreshToken, 60 * 24 * 7, '/', null, true, true, false, 'Strict'
        );
    }


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

        $admin = Auth::guard('admin-api')->user();


        $refreshToken = JWTAuth::claims(['type' => 'refresh'])
        ->fromUser($admin, JWTAuth::factory()->setTTL(60 * 24 * 7)->getTTL());

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'admin' => $admin,
        ])->cookie(
            'refresh_token', $refreshToken, 60 * 24 * 7, '/', null, true, true, false, 'Strict'
        );
    }

    // Get authenticated user details
    public function user()
    {
        return response()->json(auth('admin-api')->user());
    }

    // Logout the user
    public function logout()
    {
        auth('admin-api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

// REFRESH ACCESS TOKEN
    public function refresh(Request $request)
    {
        try {
            $refreshToken = $request->cookie('refresh_token');

            if (!$refreshToken) {
                return response()->json(['error' => 'Missing refresh token'], 401);
            }

            $newAccessToken = JWTAuth::setToken($refreshToken)->refresh();

            return response()->json([
                'access_token' => $newAccessToken,
                'token_type' => 'bearer',
                'expires_in' => Auth::guard('admin-api')->factory()->getTTL() * 60,
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Invalid or expired refresh token'], 401);
        }
    }
}
