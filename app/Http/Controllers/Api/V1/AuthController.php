<?php

    namespace App\Http\Controllers\Api\V1;

    use App\Http\Controllers\Controller;
    use App\Models\User;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;

    class AuthController extends Controller
    {
        public function register(Request $request): JsonResponse
        {
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:8|max:255|confirmed',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            if (!$user) {
                return response()->json([
                    'message' => 'User not created',
                ], 500);
            }

            $user->createSettings();
            $token = $user->createToken($validated['email'])->plainTextToken;

            return response()->json([
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ]
            ]);
        }

        public function login(Request $request): JsonResponse
        {
            $validated = $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string',
            ]);

            $user = User::whereEmail($validated['email'])->first();

            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $token = $user->createToken($validated['email'])->plainTextToken;

            return response()->json([
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ]
            ]);
        }

        public function logout(Request $request): JsonResponse
        {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logged out',
            ]);
        }
    }
