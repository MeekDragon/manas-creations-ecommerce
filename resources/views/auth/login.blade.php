<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Manas Creations</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
:root{--gold:#C9A84C;--dark:#0D0D0D;--dark2:#181818;--dark3:#242424;--glass-border:rgba(201,168,76,0.18);--text:#F0EDE6;--text-muted:#9C9080;--text-dim:#5A5248;--radius:14px}
*{margin:0;padding:0;box-sizing:border-box}
body{background:var(--dark);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:radial-gradient(ellipse 80% 60% at 50% 40%,rgba(201,168,76,.06) 0%,transparent 70%);padding:20px}
.login-card{background:var(--dark2);border:1px solid var(--glass-border);border-radius:20px;padding:40px 36px;max-width:400px;width:100%;text-align:center;box-shadow:0 20px 40px rgba(0,0,0,0.4)}
.login-icon{font-size:32px;margin-bottom:12px}
.login-title{font-family:'Playfair Display',serif;font-size:26px;font-weight:700;margin-bottom:6px}
.login-sub{font-size:13px;color:var(--text-muted);margin-bottom:24px}
.login-form{display:flex;flex-direction:column;gap:14px;text-align:left}
.login-form label{font-size:11px;letter-spacing:1.5px;text-transform:uppercase;color:var(--text-muted);font-weight:500;margin-bottom:4px;display:block}
.login-form input{width:100%;background:var(--dark3);border:1px solid var(--glass-border);border-radius:8px;padding:13px 16px;color:var(--text);font-size:14px;font-family:inherit}
.login-form input:focus{outline:none;border-color:var(--gold)}
.btn-gold{background:var(--gold);color:var(--dark);font-weight:600;padding:13px;border-radius:8px;font-size:14px;width:100%;border:none;cursor:pointer;font-family:inherit;transition:background 0.2s}
.btn-gold:hover{background:#E8C97A}
.login-error{font-size:13px;color:#E24B4A;text-align:center;padding:10px;background:rgba(226,75,74,.1);border-radius:6px;border:1px solid rgba(226,75,74,.2);line-height:1.4}
.back-link{display:block;margin-top:20px;font-size:13px;color:var(--text-dim);text-decoration:none;transition:color .2s}
.back-link:hover{color:var(--gold)}
</style>
</head>
<body>
  <div class="login-card">
    <div class="login-icon">🔐</div>
    <div class="login-title">Welcome Back</div>
    <div class="login-sub">Sign in to your account</div>

    @if($errors->any())
      <div class="login-error" style="margin-bottom:20px">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <!-- Password Form -->
    <form id="pwdForm" method="POST" action="{{ route('login.post') }}" class="login-form">
      @csrf
      <div>
        <label>Email or Mobile</label>
        <input type="text" name="login_id" value="{{ old('login_id') }}" placeholder="Email or 10-digit mobile" autocomplete="off" required>
      </div>
      <div style="position:relative">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
          <label style="margin-bottom:0">Password</label>
          <a href="{{ route('password.request') }}" style="font-size:11px;color:var(--gold);text-decoration:none">Forgot Password?</a>
        </div>
        <input type="password" name="password" id="password" placeholder="••••••••" required style="padding-right:40px">
        <button type="button" onclick="togglePwd('password', this)" tabindex="-1" style="position:absolute;right:12px;top:28px;background:none;border:none;color:var(--text-dim);cursor:pointer;font-size:16px;padding:0" title="Show/Hide Password">
          👁
        </button>
      </div>
      <div style="display:flex;align-items:center;gap:8px">
        <input type="checkbox" name="remember" id="remember" style="width:auto">
        <label for="remember" style="margin:0;text-transform:none;letter-spacing:normal">Remember me</label>
      </div>
      <button type="submit" class="btn-gold" style="margin-top:4px">Sign In</button>
    </form>
    
    <div style="margin-top: 24px; font-size: 13px; color: var(--text-muted)">
      Don't have an account? <a href="{{ route('register') }}" style="color: var(--gold); text-decoration: none;">Sign Up</a>
    </div>

    <a href="{{ route('home') }}" class="back-link">← Back to website</a>
  </div>

  <script>
  function togglePwd(id, btn) {
    const el = document.getElementById(id);
    if (el.type === 'password') {
      el.type = 'text';
      btn.textContent = '🙈';
    } else {
      el.type = 'password';
      btn.textContent = '👁';
    }
  }
  </script>
</body>
</html>
