<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password — Manas Creations</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#C9A84C;--dark:#0D0D0D;--dark2:#181818;--dark3:#242424;--glass-border:rgba(201,168,76,0.18);--text:#F0EDE6;--text-muted:#9C9080;--text-dim:#5A5248;--radius:14px}
*{margin:0;padding:0;box-sizing:border-box}
body{background:var(--dark);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:radial-gradient(ellipse 80% 60% at 50% 40%,rgba(201,168,76,.06) 0%,transparent 70%)}
.login-card{background:var(--dark2);border:1px solid var(--glass-border);border-radius:20px;padding:44px 40px;max-width:380px;width:100%;text-align:center}
.login-icon{font-size:36px;margin-bottom:16px}
.login-title{font-family:'Playfair Display',serif;font-size:26px;font-weight:700;margin-bottom:6px}
.login-sub{font-size:13px;color:var(--text-muted);margin-bottom:32px;line-height:1.5}
.login-form{display:flex;flex-direction:column;gap:14px;text-align:left}
.login-form label{font-size:11px;letter-spacing:1.5px;text-transform:uppercase;color:var(--text-muted);font-weight:500;margin-bottom:4px;display:block}
.login-form input{width:100%;background:var(--dark3);border:1px solid var(--glass-border);border-radius:8px;padding:13px 16px;color:var(--text);font-size:14px;font-family:inherit}
.login-form input:focus{outline:none;border-color:var(--gold)}
.btn-gold{background:var(--gold);color:var(--dark);font-weight:600;padding:13px;border-radius:8px;font-size:15px;width:100%;border:none;cursor:pointer;font-family:inherit}
.btn-gold:hover{background:#E8C97A}
.login-error{font-size:13px;color:#E24B4A;text-align:center;padding:10px;background:rgba(226,75,74,.1);border-radius:6px;border:1px solid rgba(226,75,74,.2)}
.password-hint{font-size:12px;color:var(--text-dim);line-height:1.5;margin-top:6px}
</style>
</head>
<body>
  <div class="login-card">
    <div class="login-icon">🔑</div>
    <div class="login-title">Reset Password</div>
    <div class="login-sub">Create a new secure password for your account.</div>

    @if($errors->any())
      <div class="login-error" style="margin-bottom:20px">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="login-form">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">
      
      <div>
        <label>Email Address</label>
        <input type="email" name="email" value="{{ $email ?? old('email') }}" readonly style="opacity:0.7">
      </div>
      
      <div style="position:relative">
        <label>New Password</label>
        <input type="password" name="password" id="password" placeholder="••••••••" required style="padding-right:40px">
        <button type="button" onclick="togglePwd('password', this)" style="position:absolute;right:12px;top:28px;background:none;border:none;color:var(--text-dim);cursor:pointer;font-size:16px;padding:0" title="Show/Hide Password">
          👁
        </button>
        <div class="password-hint">Use at least 8 characters.</div>
      </div>
      
      <div style="position:relative">
        <label>Confirm New Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required style="padding-right:40px">
        <button type="button" onclick="togglePwd('password_confirmation', this)" style="position:absolute;right:12px;top:28px;background:none;border:none;color:var(--text-dim);cursor:pointer;font-size:16px;padding:0" title="Show/Hide Password">
          👁
        </button>
      </div>

      <button type="submit" class="btn-gold" style="margin-top:8px">Reset Password</button>
    </form>
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
