<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. التحقق اليدوي (بدون قيود إيميل صارمة)
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. التحويل الذكي: لو أرقام حولها لإيميل، لو غير كدة سيبها زي ما هي
        $input = $request->input('email');
        $email = is_numeric($input) ? $input . '@adzone.com' : $input;

        // 3. المحاولة (مع تفعيل الـ Remember Me دائماً بناءً على اختيار العميل)
        if (! Auth::attempt(['email' => $email, 'password' => $request->password], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'بيانات الدخول غير صحيحة.',
            ]);
        }

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $role = $user->roles->first()->name ?? '';

        // 4. التوجيه حسب الدور
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'worker') {
            return redirect()->route('worker.dashboard');
        } elseif ($role === 'customer') {
            return redirect()->route('customer.dashboard');
        }

        return redirect('/dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
