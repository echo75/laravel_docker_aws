<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AccountSessionController extends Controller
{
    public function show(Request $request): JsonResponse|Response
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return response('null', 200, ['Content-Type' => 'application/json']);
        }

        return response()->json($this->serializeUser($user), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:1'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !$user->hash || !Hash::check($credentials['password'], $user->hash)) {
            throw ValidationException::withMessages([
                'email' => ['Die Login-Daten sind ungultig.'],
            ]);
        }

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return response()->json($this->serializeUser($user), 200);
    }

    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(null, 204);
    }

    private function serializeUser(User $user): array
    {
        return [
            'id' => $user->id,
            'firstName' => $user->firstName,
            'surName' => $user->surName,
            'email' => $user->email,
        ];
    }
}
