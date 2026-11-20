<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    private function bad($m, $c = 400)
    {
        return response()->json(['error' => $m], $c);
    }

    private function ok()
    {
        return ['me' => (string) Auth::id(), 'csrf' => csrf_token()];
    }

    private function email(Request $r): string
    {
        return strtolower(trim((string) $r->input('email')));
    }

    public function signup(Request $r)
    {
        $name = trim((string) $r->input('name'));
        $email = $this->email($r);
        $pw = (string) $r->input('pw');
        $ans = strtolower(trim((string) $r->input('ans')));

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) return $this->bad('Enter your name and a valid email.');
        if (strlen($pw) < 6) return $this->bad('Use a password with at least 6 characters.');
        if ($ans === '') return $this->bad('Add a recovery answer so you can reset your password later.');
        if (User::where('email', $email)->exists()) return $this->bad('That email already has an account. Log in instead.');

        $u = User::create([
            'name' => mb_substr($name, 0, 60),
            'email' => $email,
            'password' => $pw,
            'recovery_hash' => Hash::make($ans),
            'role' => 'member',
        ]);
        Auth::login($u, true);
        $r->session()->regenerate();
        return $this->ok();
    }

    public function login(Request $r)
    {
        $key = 'login|' . $this->email($r) . '|' . $r->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) return $this->bad('Too many attempts. Try again in 15 minutes.', 429);
        if (!Auth::attempt(['email' => $this->email($r), 'password' => (string) $r->input('pw')], true)) {
            RateLimiter::hit($key, 900);
            return $this->bad('Wrong email or password.', 401);
        }
        RateLimiter::clear($key);
        $r->session()->regenerate();
        return $this->ok();
    }

    public function reset(Request $r)
    {
        $key = 'reset|' . $this->email($r) . '|' . $r->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) return $this->bad('Too many attempts. Try again in 15 minutes.', 429);
        $u = User::where('email', $this->email($r))->first();
        if (!$u || !Hash::check(strtolower(trim((string) $r->input('ans'))), $u->recovery_hash)) {
            RateLimiter::hit($key, 900);
            return $this->bad('The email or recovery answer doesn’t match.', 401);
        }
        $pw = (string) $r->input('pw');
        if (strlen($pw) < 6) return $this->bad('Use a new password with at least 6 characters.');
        $u->password = $pw;
        $u->save();
        RateLimiter::clear($key);
        return ['ok' => 1];
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return ['csrf' => csrf_token()];
    }
}
