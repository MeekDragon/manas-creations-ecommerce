<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Account - Manas Creations</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#C9A84C;--dark:#0D0D0D;--dark2:#181818;--dark3:#242424;--glass-border:rgba(201,168,76,0.18);--text:#F0EDE6;--text-muted:#9C9080;--text-dim:#5A5248}
*{margin:0;padding:0;box-sizing:border-box}
body{background:var(--dark);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;background:radial-gradient(ellipse 80% 60% at 50% 40%,rgba(201,168,76,.06) 0%,transparent 70%)}
.verify-card{background:var(--dark2);border:1px solid var(--glass-border);border-radius:20px;padding:44px 40px;max-width:440px;width:100%;text-align:center;box-shadow:0 20px 40px rgba(0,0,0,0.4)}
.verify-icon{font-size:42px;margin-bottom:16px;animation: pulse 2s infinite ease-in-out;}
@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.08); text-shadow: 0 0 10px var(--gold); }
  100% { transform: scale(1); }
}
.verify-title{font-family:'Playfair Display',serif;font-size:26px;font-weight:700;margin-bottom:12px;color:var(--gold)}
.verify-sub{font-size:14px;color:var(--text-muted);margin-bottom:26px;line-height:1.7}
.verify-email{color:#fff;font-weight:600;background:rgba(201,168,76,0.12);padding:4px 8px;border-radius:6px;border:1px solid var(--glass-border);display:inline-block;margin:4px 0}
.btn-gold{background:var(--gold);color:var(--dark);font-weight:600;padding:13px;border-radius:8px;font-size:15px;width:100%;border:none;cursor:pointer;font-family:inherit;transition:all 0.2s}
.btn-gold:hover{background:#E8C97A}
.btn-link{background:none;border:none;color:var(--gold);cursor:pointer;font-family:inherit;font-size:13px;transition:color 0.2s;text-decoration:none;display:inline-block}
.btn-link:hover{color:#E8C97A;text-decoration:underline}
.notice{font-size:13px;text-align:center;padding:12px;border-radius:8px;margin-bottom:22px;line-height:1.4}
.notice.error{color:#E24B4A;background:rgba(226,75,74,.1);border:1px solid rgba(226,75,74,.2)}
.notice.success{color:var(--gold);background:rgba(201,168,76,.08);border:1px solid rgba(201,168,76,.2)}
.back-link{display:block;margin-top:20px;font-size:13px;color:var(--text-dim);text-decoration:none;transition:color .2s}
.back-link:hover{color:var(--gold)}
</style>
</head>
<body>
  <div class="verify-card">
    <div class="verify-icon">✉️</div>
    <div class="verify-title">Verify Your Email</div>
    
    <div class="verify-sub">
      We have sent a secure, one-click activation link to your email address:<br>
      <span class="verify-email">{{ $user->email }}</span><br>
      Please check your inbox and click the link to activate your account and gain access to the platform.
    </div>

    @if(session('success'))
      <div class="notice success">✨ {{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="notice error">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('verification.resend') }}" style="margin-top:8px">
      @csrf
      <button type="submit" class="btn-gold">Resend Verification Email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="margin-top:18px">
      @csrf
      <button type="submit" class="btn-link" style="color:var(--text-dim)">← Back to Login / Use another account</button>
    </form>
  </div>

  <script>
    // Poll the backend verification status every 3 seconds
    // to automatically log in the user as soon as they click the verification link in their email!
    setInterval(async () => {
      try {
        const res = await fetch('{{ route("verification.status", [], false) }}');
        if (res.ok) {
          const data = await res.json();
          if (data.verified) {
            // Elegant micro-transition into verified celebration state
            const card = document.querySelector('.verify-card');
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '0';
            setTimeout(() => {
              card.innerHTML = `
                <div class="verify-icon" style="font-size:52px;animation:none">🎉</div>
                <div class="verify-title" style="margin-top:16px;color:var(--gold)">Account Activated!</div>
                <div class="verify-sub" style="margin-bottom:0;color:var(--text)">
                  ✨ Your email is verified. Redirecting you to the home page...
                </div>
              `;
              card.style.opacity = '1';
            }, 500);

            setTimeout(() => {
              window.location.href = '{{ route("home") }}';
            }, 2000);
          }
        }
      } catch (e) {
        console.error("Error checking verification status:", e);
      }
    }, 3000);
  </script>
</body>
</html>
