<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password — Manas Creations</title>
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
.login-success{font-size:13px;color:#4CAF50;text-align:center;padding:10px;background:rgba(76,175,80,.1);border-radius:6px;border:1px solid rgba(76,175,80,.2);margin-bottom:20px}
.back-link{display:block;margin-top:20px;font-size:13px;color:var(--text-dim);text-decoration:none;transition:color .2s}
.back-link:hover{color:var(--gold)}
</style>
</head>
<body>
  <div class="login-card">
    <div class="login-icon">🔒</div>
    <div class="login-title">Forgot Password</div>
    <div class="login-sub">Enter your email address and we will send you a password reset link.</div>

    @if (session('status'))
        <div class="login-success">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
      <div class="login-error" style="margin-bottom:20px">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="login-form">
      @csrf
      <div>
        <label>Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" autocomplete="off" required>
      </div>
      <button type="submit" class="btn-gold" style="margin-top:8px">Send Reset Link</button>
    </form>
    
    <a href="{{ route('login') }}" class="back-link">← Back to Login</a>
  </div>
</body>
</html>
