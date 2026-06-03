<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Cek akun suspended
        if ($user->status_akun === 'suspended') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['email' => 'Akun kamu telah disuspend. Hubungi admin untuk informasi lebih lanjut.']);
        }

        // Admin -> dashboard admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // EO / Pengelola -> dashboard pengelola
        if ($user->isPengelola()) {
            return redirect()->route('pengelola.dashboard');
        }

        // User biasa -> beranda
        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
