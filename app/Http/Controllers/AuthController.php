<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
            'password_confirmation' => 'required'
        ],
        [
            'name.required' => ':attribute should not be null',
            'email.required' => ':attribute should not be null',
            'password.required' => ':attribute should not be null',
            'password_confirmation.required' => ':attribute should not be null'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['success' => 'Your account registered'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages(['email' => 'Email or password does not match']);
        } 
        
        return $request->session()->regenerate();
    }

    public function logout(Request $request)
    {
        return Auth::guard('web')->logout();
    }
}
