<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', '✨ Premium Acrylic Products — Manas Creations')</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  --gold:#C9A84C;--gold-light:#E8C97A;--gold-pale:#F5E9C8;
  --dark:#0D0D0D;--dark-rgb:13,13,13;
  --dark2:#181818;--dark3:#242424;--dark4:#2E2E2E;
  --glass:rgba(255,255,255,0.03);--glass-border:rgba(201,168,76,0.18);
  --text:#F0EDE6;--text-rgb:240,237,230;
  --text-muted:#9C9080;--text-dim:#5A5248;
  --radius:14px;--tr:0.3s cubic-bezier(.4,0,.2,1);
}
:root.light-mode{
  --dark:#F4F2EC;--dark-rgb:244,242,236;
  --dark2:#EAE6DF;--dark3:#FFFFFF;--dark4:#F8F7F4;
  --glass:rgba(0,0,0,0.04);--glass-border:rgba(201,168,76,0.3);
  --text:#1C1A17;--text-rgb:28,26,23;
  --text-muted:#6B655C;--text-dim:#8F887C;
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{background:var(--dark);color:var(--text);font-family:'DM Sans',sans-serif;overflow-x:hidden}
a{text-decoration:none;color:inherit}
button{cursor:pointer;border:none;outline:none;font-family:inherit}
input,textarea,select{font-family:inherit}
.hidden{display:none!important}
.gold{color:var(--gold)}
.btn-gold{background:var(--gold);color:var(--dark);font-weight:600;padding:12px 28px;border-radius:8px;font-size:14px;transition:var(--tr);letter-spacing:.5px;display:inline-block;border:none;cursor:pointer}
.btn-gold:hover{background:var(--gold-light);transform:translateY(-1px)}
.btn-outline{background:transparent;color:var(--gold);font-weight:500;padding:10px 24px;border-radius:8px;font-size:14px;border:1.5px solid var(--gold);transition:var(--tr);display:inline-block;cursor:pointer}
.btn-outline:hover{background:rgba(201,168,76,.1)}
::-webkit-scrollbar{width:6px}
::-webkit-scrollbar-track{background:var(--dark2)}
::-webkit-scrollbar-thumb{background:var(--gold);border-radius:3px}

/* ── TOAST ── */
#toast{position:fixed;bottom:28px;right:28px;z-index:999;background:var(--dark3);border:1px solid var(--glass-border);border-left:3px solid var(--gold);border-radius:8px;padding:14px 20px;font-size:13px;color:var(--text);transform:translateY(80px);opacity:0;transition:var(--tr);max-width:300px;pointer-events:none}
#toast.show{transform:translateY(0);opacity:1}
#toast.error{border-left-color:#E24B4A}

@keyframes fadeUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
@keyframes scrollPulse{0%,100%{opacity:.4}50%{opacity:1}}
@keyframes spin{to{transform:rotate(360deg)}}

@yield('styles')
</style>
@stack('head')
<script>
  if ((localStorage.getItem('theme_public') || 'light') === 'light') {
    document.documentElement.classList.add('light-mode');
  }
</script>
</head>
<body>

<div id="toast"></div>

@yield('content')

<script>
// Global CSRF token for AJAX
window.CSRF_TOKEN = '{{ csrf_token() }}';

// Toast utility
let toastTimer;
function showToast(msg, type=''){
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = type === 'error' ? 'show error' : 'show';
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.className = '', 3500);
}

// Show Laravel session flash messages as toasts
@if(session('success'))
  document.addEventListener('DOMContentLoaded', () => showToast({{ Js::from(session('success')) }}));
@endif
@if(session('error'))
  document.addEventListener('DOMContentLoaded', () => showToast({{ Js::from(session('error')) }}, 'error'));
@endif

// Theme handling
function updateThemeIcons() {
  const isLight = document.documentElement.classList.contains('light-mode');
  const sunIcon = `<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>`;
  const moonIcon = `<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>`;
  document.querySelectorAll('.theme-toggle-icon').forEach(icon => {
    icon.innerHTML = isLight ? moonIcon : sunIcon;
  });
}

window.toggleTheme = function() {
  const isLight = document.documentElement.classList.toggle('light-mode');
  localStorage.setItem('theme_public', isLight ? 'light' : 'dark');
  updateThemeIcons();
};

window.addEventListener('storage', (e) => {
  if (e.key === 'theme_public') {
    if (e.newValue === 'light') document.documentElement.classList.add('light-mode');
    else document.documentElement.classList.remove('light-mode');
    updateThemeIcons();
  }
});

document.addEventListener('DOMContentLoaded', updateThemeIcons);
</script>

@stack('scripts')
</body>
</html>
