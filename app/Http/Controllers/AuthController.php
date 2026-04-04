<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:128'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(12)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'], // каст в модели уже захеширует
            'role'     => User::ROLE_USER,        // по умолчанию 'user'
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registrácia prebehla úspešne.',
            'user'    => $user,
            'token'   => $token,
        ], Response::HTTP_CREATED);
    }
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Nesprávny email alebo heslo.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Prihlásenie bolo úspešné.',
            'user'    => $user,
            'token'   => $token,
        ], Response::HTTP_OK);
    }
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Odhlásenie prebehlo úspešne.',
        ], Response::HTTP_OK);
    }
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Odhlásenie zo všetkých zariadení prebehlo úspešne.',
        ], Response::HTTP_OK);
    }
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'confirmed', Password::min(12)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Aktuálne heslo je nesprávne.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user->password = $validated['new_password'];
        $user->save();

        return response()->json([
            'message' => 'Heslo bolo úspešne zmenené.',
        ], Response::HTTP_OK);
    }
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'min:2', 'max:128'],
            'role' => ['sometimes', 'in:user,admin'],
            'premium_until' => ['sometimes', 'date', 'nullable'],
        ]);

        $user = $request->user();
        $user->update($validated);

        return response()->json([
            'message' => 'Profil bol úspešne aktualizovaný.',
            'user'    => $user,
        ], Response::HTTP_OK);
    }
}
