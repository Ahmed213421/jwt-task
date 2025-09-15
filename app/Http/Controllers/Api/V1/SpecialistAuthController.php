<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecialistLoginRequest;
use App\Http\Requests\SpecialistRegisterRequest;
use App\Models\Specialist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class SpecialistAuthController extends Controller
{
    /**
     * Register a new specialist
     */
    public function register(Request $request)
    {
        $specialist = Specialist::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile,
            'type' => $request->type,
            'bio' => $request->bio,
            'is_active' => true,
        ]);

        $token = $specialist->createToken('specialist-access-token')->plainTextToken;

        return response()->json([
            'message' => 'Specialist registered successfully',
            'specialist' => $specialist,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }


    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
    }
        $specialist = Specialist::where('email', $request->email)->first();

        if (!$specialist || !Hash::check($request->password, $specialist->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$specialist->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account is deactivated. Please contact support.'],
            ]);
        }


        $token = $specialist->createToken('specialist-access-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'specialist' => $specialist,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function specialist(Request $request)
    {
        return response()->json([
            'specialist' => $request->user()
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

}
