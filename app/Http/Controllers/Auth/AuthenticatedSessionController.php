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
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Tangani permintaan login yang masuk.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Proses autentikasi (Validasi email, password, dan status aktif ada di LoginRequest)
        $request->authenticate();

        $request->session()->regenerate();

        // LOGIC TAMBAHAN: Redirect Berdasarkan Role
        $user = Auth::user();

     if ($user->hasRole('Driver')) {
    return redirect()->intended(route('driver.index')); // Ubah jadi driver.index
}

        // Jika Admin / Operator / Lainnya, arahkan ke Dashboard utama
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Hancurkan sesi yang diautentikasi (Logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
