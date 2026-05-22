<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (! Auth::user()->isVerified()) {
                return redirect()->route('verification.notice');
            }

            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login_id' => 'required',
            'password' => 'required',
        ]);
        
        $loginId = trim($credentials['login_id']);
        
        // Handle literal 'admin' input mapping to seeded admin email
        if (Str::lower($loginId) === 'admin') {
            $loginId = 'manascreationsofficial@gmail.com';
        }
        
        $loginField = filter_var($loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        
        $attemptCreds = [
            $loginField => $loginField === 'email' ? Str::lower($loginId) : $loginId,
            'password' => $credentials['password']
        ];

        if (Auth::attempt($attemptCreds, $request->has('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (! $user->isVerified()) {
                // Generate secure verification link
                $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                    'verification.verify.email',
                    now()->addMinutes(60),
                    ['id' => $user->id, 'hash' => sha1($user->email)]
                );

                // Send email via Brevo SMTP
                try {
                    Mail::to($user->email)->send(new \App\Mail\VerifyEmailMail($url));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to send verification email on login attempt: " . $e->getMessage());
                }

                // Log out immediately
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Put user ID in session to allow verification status polling / resend on the notice page
                $request->session()->put('otp_user_id', $user->id);
                $request->session()->save();

                return redirect()->route('verification.notice')
                    ->withErrors(['login_id' => "Your email is not verified. We've sent a verification link to your inbox at {$user->email}. Please verify to log in."]);
            }
            
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('home');
        }

        return back()->withErrors([
            'login_id' => 'The provided credentials do not match our records.',
        ])->onlyInput('login_id');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            if (! Auth::user()->isVerified()) {
                return redirect()->route('verification.notice');
            }

            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Clean up completely unverified auto-created placeholder users (from OTP click-away)
        // to prevent the frustrating "Already registered" validation loop!
        if (!empty($request->email) || !empty($request->mobile)) {
            User::where(function($q) use ($request) {
                if (!empty($request->email)) {
                    $q->where('email', Str::lower($request->email));
                }
                if (!empty($request->mobile)) {
                    $q->orWhere('mobile', $request->mobile);
                }
            })
            ->whereNull('email_verified_at')
            ->whereNull('mobile_verified_at')
            ->forceDelete();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'email' => $this->registrationEmailRules(),
            'password' => ['required', 'string', 'confirmed', PasswordRule::min(8)->letters()->numbers()->symbols()],
        ], $this->registrationValidationMessages());

        $fullName = trim($request->name) . ' ' . trim($request->surname);

        $user = User::create([
            'name' => $fullName,
            'mobile' => $request->mobile,
            'email' => Str::lower($request->email),
            'password' => Hash::make($request->password),
            'is_admin' => false,
        ]);

        // Put user ID in session to allow verification resend / email retrieval on the notice page
        $request->session()->put('otp_user_id', $user->id);

        // Generate secure verification link
        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify.email',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // Send email via Brevo SMTP
        try {
            Mail::to($user->email)->send(new \App\Mail\VerifyEmailMail($url));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send verification email on registration: " . $e->getMessage());
        }

        return redirect()->route('verification.notice')
            ->with('success', 'Verification link sent to your email.');
    }

    public function showVerifyEmail(Request $request)
    {
        $user = $this->otpUser($request);

        if (! $user) {
            return redirect()->route('login')
                ->withErrors(['login_id' => 'Please sign in to verify your account.']);
        }

        if ($user->isVerified()) {
            return redirect()->route($user->is_admin ? 'admin.dashboard' : 'home');
        }

        return view('auth.verify-mobile', compact('user'));
    }

    public function resendEmailOtp(Request $request)
    {
        $user = $this->otpUser($request);

        if (! $user) {
            return redirect()->route('login')
                ->withErrors(['login_id' => 'Please sign in to verify your account.']);
        }

        if ($user->isVerified()) {
            return redirect()->route($user->is_admin ? 'admin.dashboard' : 'home');
        }

        // Generate secure verification link
        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify.email',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // Send email via Brevo SMTP
        try {
            Mail::to($user->email)->send(new \App\Mail\VerifyEmailMail($url));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to resend verification email: " . $e->getMessage());
            return back()->withErrors(['otp' => 'Failed to send verification email. Please try again later.']);
        }

        return back()->with('success', 'A new verification link has been sent to your email.');
    }

    public function sendVerificationEmail(Request $request)
    {
        $user = $request->user();

        // Allow updating email if it is currently a placeholder (starts with 'mobile_')
        if ($request->filled('email') && Str::startsWith($user->email, 'mobile_')) {
            $request->validate([
                'email' => [
                    'required',
                    'string',
                    'email:rfc,dns',
                    'max:255',
                    'unique:users,email',
                    function (string $attribute, mixed $value, \Closure $fail): void {
                        $guardrail = new \App\Services\EmailGuardrailService();
                        $errorMsg = '';
                        if (!$guardrail->validate($value, $errorMsg)) {
                            $fail($errorMsg);
                        }
                    },
                ]
            ]);

            $user->forceFill([
                'email' => Str::lower($request->email),
                'email_verified_at' => null, // reset email verification if they set a new email
            ])->save();
        }

        if ($user->hasVerifiedEmail()) {
            return back()->with('success', 'Email is already verified.');
        }

        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify.email',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // Send gold-accented premium HTML template
        Mail::to($user->email)->send(new \App\Mail\VerifyEmailMail($url));

        return back()->with('success', 'Verification link sent to your email.');
    }

    public function verifyEmailLink(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (sha1($user->email) !== $hash) {
            abort(403);
        }

        if (!$user->hasVerifiedEmail()) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        // Authenticate and log the user in immediately
        Auth::login($user, true);

        // Clear the pending verification user ID from session
        $request->session()->forget('otp_user_id');
        $request->session()->save();

        return redirect()->route('home')->with('success', 'Email verified successfully. Welcome to Manas Creations!');
    }

    public function checkVerificationStatus(Request $request)
    {
        $user = $this->otpUser($request);

        if (!$user) {
            return response()->json(['verified' => false]);
        }

        if ($user->isVerified()) {
            // Automatically log them in on this session/device
            if (!Auth::check()) {
                Auth::login($user, true);
            }
            
            // Clear the pending verification user ID from session
            $request->session()->forget('otp_user_id');
            $request->session()->save();

            return response()->json(['verified' => true]);
        }

        return response()->json(['verified' => false]);
    }

    public function redirectToGoogle(Request $request)
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google login is not configured yet.']);
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'prompt' => 'select_account',
        ]);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    public function handleGoogleCallback(Request $request)
    {
        if ($request->filled('error')) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google login was cancelled.']);
        }

        $state = $request->session()->pull('google_oauth_state');
        if (! $state || ! hash_equals($state, (string) $request->state)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google login session expired. Please try again.']);
        }

        if (! $request->filled('code')) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google did not return an authorization code.']);
        }

        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect'),
            'grant_type' => 'authorization_code',
            'code' => $request->code,
        ]);

        if (! $tokenResponse->successful() || ! $tokenResponse->json('access_token')) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google login failed while fetching your profile.']);
        }

        $profileResponse = Http::withToken($tokenResponse->json('access_token'))
            ->get('https://www.googleapis.com/oauth2/v3/userinfo');

        if (! $profileResponse->successful()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google login failed while reading your profile.']);
        }

        $profile = $profileResponse->json();
        if (empty($profile['email']) || empty($profile['email_verified'])) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Please use a verified Google email address.']);
        }

        $user = $this->findOrCreateGoogleUser($profile);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended($user->is_admin ? route('admin.dashboard') : route('home'))
            ->with('success', 'Signed in with Google successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function otpUser(Request $request): ?User
    {
        if (Auth::check()) {
            return Auth::user();
        }

        $userId = $request->session()->get('otp_user_id');

        return $userId ? User::find($userId) : null;
    }


    private function registrationEmailRules(): array
    {
        return [
            'required',
            'string',
            'email:rfc,dns',
            'max:255',
            'unique:users,email',
            function (string $attribute, mixed $value, \Closure $fail): void {
                $guardrail = new \App\Services\EmailGuardrailService();
                $errorMsg = '';
                if (!$guardrail->validate($value, $errorMsg)) {
                    $fail($errorMsg);
                }
            },
        ];
    }



    // ── Firebase Token Callback Endpoints ────────────────

    public function handleFirebaseCallback(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        $verifier = new \App\Services\FirebaseTokenVerifier();
        $payload = $verifier->verify($request->id_token);

        if (!$payload || empty($payload['email'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Firebase token.'
            ], 422);
        }

        $email = Str::lower($payload['email']);

        // Check guardrail rules
        $guardrail = new \App\Services\EmailGuardrailService();
        $errorMsg = '';
        if (!$guardrail->validate($email, $errorMsg)) {
            return response()->json([
                'success' => false,
                'message' => $errorMsg
            ], 422);
        }

        // Find or create user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $payload['name'] ?? Str::before($email, '@'),
                'email' => $email,
                'password' => Hash::make(Str::random(32)),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]);
        } else {
            $user->forceFill([
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        $redirect = $user->is_admin ? route('admin.dashboard') : route('home');

        return response()->json([
            'success' => true,
            'redirect' => $redirect,
            'message' => 'Firebase authentication successful!'
        ]);
    }

    private function registrationValidationMessages(): array
    {
        return [
            'email.email' => 'Please enter a valid email id.',
            'email.unique' => 'This email is already registered.',
            'password.letters' => 'Password must contain at least one alphabet.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character.',
        ];
    }

    private function findOrCreateGoogleUser(array $profile): User
    {
        $email = Str::lower($profile['email']);
        $googleId = (string) ($profile['sub'] ?? '');

        $user = User::where('google_id', $googleId)
            ->orWhere('email', $email)
            ->first();

        if (! $user) {
            return User::create([
                'name' => $profile['name'] ?? Str::before($email, '@'),
                'email' => $email,
                'password' => Hash::make(Str::random(40)),
                'google_id' => $googleId,
                'avatar' => $profile['picture'] ?? null,
                'email_verified_at' => now(),
                'is_admin' => false,
            ]);
        }

        $user->forceFill([
            'google_id' => $user->google_id ?: $googleId,
            'avatar' => $profile['picture'] ?? $user->avatar,
            'email_verified_at' => $user->email_verified_at ?? now(),
            'email_otp_code' => null,
            'email_otp_expires_at' => null,
            'email_otp_sent_at' => null,
        ])->save();

        return $user;
    }

    // ── Password Reset ─────────────────────────────────
    public function showForgotPassword()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
