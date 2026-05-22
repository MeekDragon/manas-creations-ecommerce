<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — Manas Creations</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#C9A84C;--dark:#0D0D0D;--dark2:#181818;--dark3:#242424;--glass-border:rgba(201,168,76,0.18);--text:#F0EDE6;--text-muted:#9C9080;--text-dim:#5A5248;--radius:14px}
*{margin:0;padding:0;box-sizing:border-box}
body{background:var(--dark);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:radial-gradient(ellipse 80% 60% at 50% 40%,rgba(201,168,76,.06) 0%,transparent 70%)}
.login-card{background:var(--dark2);border:1px solid var(--glass-border);border-radius:20px;padding:44px 40px;max-width:400px;width:100%;text-align:center}
.login-icon{font-size:36px;margin-bottom:16px}
.login-title{font-family:'Playfair Display',serif;font-size:26px;font-weight:700;margin-bottom:6px}
.login-sub{font-size:13px;color:var(--text-muted);margin-bottom:32px}
.login-form{display:flex;flex-direction:column;gap:14px;text-align:left}
.login-form label{font-size:11px;letter-spacing:1.5px;text-transform:uppercase;color:var(--text-muted);font-weight:500;margin-bottom:4px;display:block}
.login-form input{width:100%;background:var(--dark3);border:1px solid var(--glass-border);border-radius:8px;padding:13px 16px;color:var(--text);font-size:14px;font-family:inherit}
.login-form input:focus{outline:none;border-color:var(--gold)}
.btn-gold{background:var(--gold);color:var(--dark);font-weight:600;padding:13px;border-radius:8px;font-size:15px;width:100%;border:none;cursor:pointer;font-family:inherit}
.btn-gold:hover{background:#E8C97A}
.google-btn{display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:12px;border-radius:8px;background:#fff;color:#1f1f1f;font-size:14px;font-weight:600;text-decoration:none;margin-bottom:18px}
.google-mark{width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#f5f5f5;color:#4285F4;font-weight:700;font-size:15px}
.auth-divider{display:flex;align-items:center;gap:12px;margin:0 0 18px;color:var(--text-dim);font-size:11px;text-transform:uppercase;letter-spacing:1.4px}
.auth-divider::before,.auth-divider::after{content:'';height:1px;background:var(--glass-border);flex:1}
.password-hint{font-size:12px;color:var(--text-dim);line-height:1.5;margin-top:6px}
.login-error{font-size:13px;color:#E24B4A;text-align:center;padding:10px;background:rgba(226,75,74,.1);border-radius:6px;border:1px solid rgba(226,75,74,.2)}
.back-link{display:block;margin-top:20px;font-size:13px;color:var(--text-dim);text-decoration:none;transition:color .2s}
.back-link:hover{color:var(--gold)}
</style>
</head>
<body>
  <div class="login-card">
    <div class="login-icon">👋</div>
    <div class="login-title">Create Account</div>
    <div class="login-sub">Join Manas Creations</div>

    @if($errors->any())
      <div class="login-error" style="margin-bottom:20px">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif



    <form method="POST" action="{{ route('register.post') }}" class="login-form">
      @csrf
      <div style="display:flex;gap:12px;">
        <div style="flex:1;">
          <label>First Name</label>
          <input type="text" name="name" value="{{ old('name') }}" placeholder="First Name" required>
        </div>
        <div style="flex:1;">
          <label>Surname</label>
          <input type="text" name="surname" value="{{ old('surname') }}" placeholder="Last Name" required>
        </div>
      </div>
      <div>
        <label>Mobile Number / Phone</label>
        <input type="tel" name="mobile" value="{{ old('mobile') }}" placeholder="10-digit mobile number" required pattern="[0-9]{10}">
      </div>
      <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" autocomplete="off" required>
      </div>
      <div style="position:relative">
        <label>Password</label>
        <input type="password" name="password" id="password" placeholder="••••••••" required style="padding-right:40px">
        <button type="button" onclick="togglePwd('password', this)" tabindex="-1" style="position:absolute;right:12px;top:28px;background:none;border:none;color:var(--text-dim);cursor:pointer;font-size:16px;padding:0" title="Show/Hide Password">
          👁
        </button>
        <div class="password-hint">Use at least 8 characters with alphabets, numbers, and a special character.</div>
      </div>
      <div style="position:relative">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required style="padding-right:40px">
        <button type="button" onclick="togglePwd('password_confirmation', this)" tabindex="-1" style="position:absolute;right:12px;top:28px;background:none;border:none;color:var(--text-dim);cursor:pointer;font-size:16px;padding:0" title="Show/Hide Password">
          👁
        </button>
      </div>
      <button type="submit" class="btn-gold" style="margin-top:8px">Sign Up</button>
    </form>
    
    <div style="margin-top: 20px; font-size: 13px; color: var(--text-muted)">
      Already have an account? <a href="{{ route('login') }}" style="color: var(--gold); text-decoration: none;">Sign In</a>
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
