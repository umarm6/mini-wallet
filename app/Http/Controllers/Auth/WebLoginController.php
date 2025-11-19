<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Response;

class WebLoginController extends Controller
{
    public function create(): Response
    {
        return inertia('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;
        session(['sanctum_token' => $token]);

        return redirect()
            ->intended(route('dashboard', absolute: false))
            ->with('sanctum_token', $token);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::user()->tokens()->delete();
        Auth::guard('web')->logout();
        $request->session()->invalidateToken();

        return redirect('/');
    }
}
