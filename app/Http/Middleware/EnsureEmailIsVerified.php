<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Please login before sending an inquiry.'], 401);
            }

            return redirect()->route('login')
                ->withErrors(['email' => 'Please login before sending an inquiry.']);
        }

        if (! Auth::user()->isVerified()) {
            $email = Auth::user()->email;
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Put user ID in session after invalidate to allow verification status polling / resend on the notice page
            $request->session()->put('otp_user_id', Auth::user()->id);
            $request->session()->save();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Please verify your email address to log in.'], 403);
            }

            return redirect()->route('verification.notice')
                ->withErrors(['login_id' => "Your email is not verified. We've sent a verification link to your inbox at {$email}. Please verify to log in."]);
        }

        return $next($request);
    }
}
