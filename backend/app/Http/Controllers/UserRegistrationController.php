<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserRegistrationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'firstName' => ['required', 'string', 'max:100'],
            'surName' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:6', 'max:255'],
        ]);

        $user = User::create([
            'id' => bin2hex(random_bytes(12)),
            'firstName' => $validated['firstName'],
            'surName' => $validated['surName'],
            'email' => $validated['email'],
            'salt' => null,
            'hash' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'id' => $user->id,
            'firstName' => $user->firstName,
            'surName' => $user->surName,
            'email' => $user->email,
        ], 201);
    }
}
