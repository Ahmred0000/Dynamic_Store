<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. التحقق من صحة البيانات (الاسم ورقم التليفون فقط)
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:10', 'unique:'.User::class],
        ]);

        // 2. توليد إيميل وهمي للعميل (لأغراض توافق لارافيل)
        $generatedEmail = $request->phone . '@adzone.com';

        // 3. إنشاء المستخدم مع تشفير رقم التليفون ككلمة مرور
        $user = User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'email'    => $generatedEmail,
            'password' => Hash::make($request->phone),
        ]);

        // 4. إسناد دور "العميل" عبر Spatie
        $user->assignRole('customer');

        event(new Registered($user));

        // 5. تسجيل الدخول التلقائي مع تفعيل "تذكرني" (true) ليظل العميل مسجلاً دائماً
        Auth::login($user, true);

        // 6. توجيه العميل للوحة التحكم الخاصة به
        return redirect()->route('customer.dashboard')
                         ->with('status', 'تم إنشاء حسابك بنجاح! تم تسجيل دخولك تلقائياً.');
    }
}
