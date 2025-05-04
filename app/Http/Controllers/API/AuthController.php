<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Doctor;

class AuthController extends Controller
{
    // تسجيل جديد
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'patient', // ثابت للمريض
        ]);
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json(['token' => $token, 'user' => $user], 201);
    }

    
    public function registerDoctor(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'specialization' => 'required|string',
        'phone' => 'nullable|string',
        'bio' => 'nullable|string',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'doctor',
    ]);

    Doctor::create([
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'specialization' => $validated['specialization'],
        'phone' => $validated['phone'] ?? null,
        'bio' => $validated['bio'] ?? null,
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user], 201);
}

    

    // تسجيل دخول
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    // الخروج
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }

    // بيانات المستخدم الحالي
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
