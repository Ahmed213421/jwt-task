<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Register a new admin
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8',
            'mobile' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile,
        ]);

        // Create access token
        $accessToken = $admin->createToken('admin-access-token')->plainTextToken;

        return response()->json([
            'message' => 'Registered successfully',
            'admin' => $admin,
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string|min:5',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find admin by mobile
        $admin = Admin::where('mobile', $request->mobile)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Revoke existing tokens (optional - for single session)
        // $admin->tokens()->delete();

        // Create new access token
        $accessToken = $admin->createToken('admin-access-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'admin' => $admin,
        ]);
    }

    // Get authenticated admin details
    public function user(Request $request)
    {
        return response()->json([
            'admin' => $request->user()
        ]);
    }

    // Logout the admin
    public function logout(Request $request)
    {
        // Revoke the current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    // Logout from all devices
    public function logoutAll(Request $request)
    {
        // Revoke all tokens for the admin
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out from all devices'
        ]);
    }

    // Get all active tokens (optional)
    public function tokens(Request $request)
    {
        $tokens = $request->user()->tokens()->get(['id', 'name', 'created_at', 'last_used_at']);

        return response()->json([
            'tokens' => $tokens
        ]);
    }

    // Revoke specific token (optional)
    public function revokeToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token_id' => 'required|integer|exists:personal_access_tokens,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $request->user()->tokens()->where('id', $request->token_id)->delete();

        return response()->json([
            'message' => 'Token revoked successfully'
        ]);
    }
}
