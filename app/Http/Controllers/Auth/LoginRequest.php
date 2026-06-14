<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // حذفنا أي قيود (مثل email أو غيرها) وخليناها مطلوبة كـ string فقط
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $input = $this->input('email');

        // التحويل الذكي: لو أرقام يبقى إيميل، لو غير كدة يبقى إيميل عادي
        $email = is_numeric($input) ? $input . '@adzone.com' : $input;

        if (! Auth::attempt(['email' => $email, 'password' => $this->input('password')], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'بيانات الدخول غير صحيحة.', // هنا كتبنا رسالة مخصصة بدلاً من trans('auth.failed') لضمان الوضوح
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'لقد قمت بمحاولات كثيرة جداً، يرجى الانتظار ' . ceil($seconds / 60) . ' دقيقة.',
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
