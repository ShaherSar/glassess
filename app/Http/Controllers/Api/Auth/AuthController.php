<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        $user = User::query()->where('email', $request->get('email'))->first();

        if ($user && Hash::check($request->get('password'), $user->password)) {
            return response()->json([
                'token' => $user->createToken($request->get('device_name'))->plainTextToken
            ]);
        }

        return response()->json([
            'error' => 'The provided credentials are incorrect.',
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'currency' => 'required|exists:currencies,name'
        ]);

        $fields = [
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'type' => User::USER_TYPE,
            'currency' => Currency::query()->where('name', $request->get('currency'))->first()->id
        ];

        $userId = User::query()->insertGetId($fields);

        $user = User::query()->findOrFail($userId);

        return response()->json($user);
    }
}
