<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || (!Auth::user()->is_admin && !Auth::user()->is_superadmin)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Please login as an admin to access this area.']);
        }

        if (!Auth::user()->isVerified()) {
            $email = Auth::user()->email;
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['login_id' => "Your email is not verified. Please verify your email first."]);
        }

        return $next($request);
    }
}
