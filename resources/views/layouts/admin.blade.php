<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>🛠️ @yield('title', 'Admin') — Manas Creations</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  --gold:#C9A84C;--gold-light:#E8C97A;
  --dark:#0D0D0D;--dark2:#181818;--dark3:#242424;--dark4:#2E2E2E;
  --glass:rgba(255,255,255,0.03);--glass-border:rgba(201,168,76,0.18);
  --text:#F0EDE6;--text-muted:#9C9080;--text-dim:#5A5248;
  --radius:14px;--tr:0.3s cubic-bezier(.4,0,.2,1);
}
:root.light-mode{
  --dark:#F4F2EC;--dark2:#EAE6DF;--dark3:#FFFFFF;--dark4:#F8F7F4;
  --glass:rgba(0,0,0,0.04);--glass-border:rgba(201,168,76,0.3);
  --text:#1C1A17;--text-muted:#6B655C;--text-dim:#8F887C;
}
*{margin:0;padding:0;box-sizing:border-box}
body{background:var(--dark);color:var(--text);font-family:'DM Sans',sans-serif;display:flex;height:100vh;overflow:hidden}
a{text-decoration:none;color:inherit}
button{cursor:pointer;border:none;outline:none;font-family:inherit}
input,textarea,select{font-family:inherit}
.btn-gold{background:var(--gold);color:var(--dark);font-weight:600;padding:12px 28px;border-radius:8px;font-size:14px;transition:var(--tr);display:inline-block;border:none;cursor:pointer}
.btn-gold:hover{background:var(--gold-light)}
.btn-outline{background:transparent;color:var(--gold);font-weight:500;padding:10px 24px;border-radius:8px;font-size:14px;border:1.5px solid var(--gold);transition:var(--tr);display:inline-block;cursor:pointer}
.btn-outline:hover{background:rgba(201,168,76,.1)}
.hidden{display:none !important}
::-webkit-scrollbar{width:6px}
::-webkit-scrollbar-track{background:var(--dark2)}
::-webkit-scrollbar-thumb{background:var(--gold);border-radius:3px}

/* ── SIDEBAR ── */
#adminSidebar{width:220px;flex-shrink:0;background:var(--dark2);border-right:1px solid var(--glass-border);display:flex;flex-direction:column;padding:0 0 24px}
.sidebar-logo{padding:24px 20px 20px;border-bottom:1px solid var(--glass-border);margin-bottom:12px}
.sidebar-logo-name{font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:var(--gold)}
.sidebar-logo-sub{font-size:11px;color:var(--text-dim);margin-top:2px}
.sidebar-nav{flex:1;padding:0 12px}
.sidebar-nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;font-size:13px;color:var(--text-muted);cursor:pointer;transition:var(--tr);margin-bottom:2px;text-decoration:none}
.sidebar-nav-item:hover{background:var(--glass);color:var(--text)}
.sidebar-nav-item.active{background:var(--gold);color:var(--dark);font-weight:600}
.sidebar-nav-item svg{width:16px;height:16px;flex-shrink:0}
.sidebar-footer{padding:0 20px}
.sidebar-user{font-size:11px;color:var(--text-dim);margin-bottom:8px}
.sidebar-user strong{display:block;color:var(--text-muted);margin-top:2px;font-size:12px}
.btn-logout{width:100%;padding:9px;border-radius:7px;font-size:12px;font-weight:500;background:transparent;color:var(--text-muted);border:1px solid var(--glass-border);display:flex;align-items:center;gap:8px;transition:var(--tr);cursor:pointer}
.btn-logout:hover{border-color:var(--gold);color:var(--gold)}

/* ── CONTENT ── */
#adminContent{flex:1;overflow-y:auto;padding:36px 40px}
.admin-page-title{font-family:'Playfair Display',serif;font-size:28px;font-weight:700;margin-bottom:6px}
.admin-page-sub{font-size:14px;color:var(--text-muted);margin-bottom:32px}
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px}
.stat-card{background:var(--dark3);border:1px solid var(--glass-border);border-radius:var(--radius);padding:20px 22px;display:flex;align-items:center;justify-content:space-between}
.stat-label{font-size:12px;color:var(--text-muted);margin-bottom:6px}
.stat-value{font-size:28px;font-weight:700;color:var(--text)}
.stat-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px}
.dash-row{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}
.dash-card{background:var(--dark3);border:1px solid var(--glass-border);border-radius:var(--radius);padding:22px 24px}
.dash-card-title{font-size:13px;letter-spacing:1.5px;text-transform:uppercase;color:var(--gold);margin-bottom:18px;font-weight:600}
.cat-item-dash{display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:var(--dark4);border-radius:8px;font-size:14px;margin-bottom:8px}
.cat-item-count{background:rgba(201,168,76,.15);color:var(--gold);padding:3px 10px;border-radius:100px;font-size:12px;font-weight:600}
.quick-action-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.quick-action{background:var(--dark4);border:1px solid var(--glass-border);border-radius:10px;padding:18px;text-align:center;cursor:pointer;transition:var(--tr);display:block;text-decoration:none}
.quick-action:hover{border-color:var(--gold);background:rgba(201,168,76,.05)}
.quick-action-icon{font-size:22px;margin-bottom:8px}
.quick-action-label{font-size:13px;font-weight:600;color:var(--text);margin-bottom:3px}
.quick-action-sub{font-size:11px;color:var(--text-dim)}
.admin-table-wrap{overflow-x:auto}
.admin-table{width:100%;border-collapse:collapse;font-size:13px}
.admin-table th{text-align:left;padding:12px 16px;border-bottom:1px solid var(--glass-border);font-size:11px;letter-spacing:1.5px;text-transform:uppercase;color:var(--text-dim);font-weight:500}
.admin-table td{padding:14px 16px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
.admin-table tr:hover td{background:var(--glass)}
.badge{font-size:11px;padding:3px 10px;border-radius:100px}
.badge-pending{background:rgba(239,166,50,.15);color:#EFA632}
.badge-resolved{background:rgba(37,211,102,.12);color:#25D366}
.table-actions{display:flex;gap:8px}
.icon-btn{width:30px;height:30px;border-radius:6px;display:flex;align-items:center;justify-content:center;background:var(--dark4);border:1px solid var(--glass-border);cursor:pointer;transition:var(--tr);color:var(--text-muted);font-size:14px;text-decoration:none}
.icon-btn:hover{border-color:var(--gold);color:var(--gold)}
.icon-btn.del:hover{border-color:#E24B4A;color:#E24B4A}
.admin-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}
.admin-form-group{display:flex;flex-direction:column;gap:6px}
.admin-form-group.full{grid-column:1/-1}
.admin-form-group label{font-size:11px;letter-spacing:1.5px;text-transform:uppercase;color:var(--text-muted);font-weight:500}
.admin-form-group input,.admin-form-group textarea,.admin-form-group select{background:var(--dark3);border:1px solid var(--glass-border);border-radius:8px;padding:11px 14px;color:var(--text);font-size:14px;transition:border-color .2s}
.admin-form-group input:focus,.admin-form-group textarea:focus,.admin-form-group select:focus{outline:none;border-color:var(--gold)}
.admin-form-group select option{background:var(--dark3)}
.admin-form-group textarea{resize:vertical;min-height:80px}
.img-upload-area{border:2px dashed var(--glass-border);border-radius:10px;padding:28px;text-align:center;cursor:pointer;transition:var(--tr);position:relative}
.img-upload-area:hover{border-color:var(--gold);background:rgba(201,168,76,.04)}
.img-preview-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:10px;margin-top:14px}
.img-preview-item{position:relative;aspect-ratio:1;border-radius:8px;overflow:hidden;border:1px solid var(--glass-border)}
.img-preview-item img{width:100%;height:100%;object-fit:cover}
.del-img{position:absolute;top:4px;right:4px;width:22px;height:22px;background:rgba(226,75,74,.85);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;color:#fff;cursor:pointer;border:none}
.upload-progress{margin-top:12px;font-size:12px;color:var(--gold);text-align:center}
.field-error{font-size:12px;color:#E24B4A;margin-top:2px}
.alert-success{background:rgba(201,168,76,.08);border:1px solid rgba(201,168,76,.2);border-radius:8px;padding:14px 18px;font-size:13px;color:var(--gold);margin-bottom:20px}
.alert-error{background:rgba(226,75,74,.1);border:1px solid rgba(226,75,74,.2);border-radius:8px;padding:14px 18px;font-size:13px;color:#E24B4A;margin-bottom:20px}

/* Toast */
#toast{position:fixed;bottom:28px;right:28px;z-index:999;background:var(--dark3);border:1px solid var(--glass-border);border-left:3px solid var(--gold);border-radius:8px;padding:14px 20px;font-size:13px;color:var(--text);transform:translateY(80px);opacity:0;transition:var(--tr);max-width:300px;pointer-events:none}
#toast.show{transform:translateY(0);opacity:1}
#toast.error{border-left-color:#E24B4A}

@media(max-width:768px){
  .stats-grid{grid-template-columns:1fr 1fr}
  .dash-row{grid-template-columns:1fr}
  .admin-form-grid{grid-template-columns:1fr}
  .quick-action-grid{grid-template-columns:1fr 1fr}
  #adminSidebar{width:180px}
  #adminContent{padding:24px 20px}
}
@media(max-width:500px){.stats-grid{grid-template-columns:1fr}}
</style>
<script>
  if ((localStorage.getItem('theme_admin') || 'light') === 'light') {
    document.documentElement.classList.add('light-mode');
  }
</script>
</head>
<body>

<div id="toast"></div>

<!-- ── SIDEBAR ── -->
<div id="adminSidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-name">Manas Creations</div>
    <div class="sidebar-logo-sub">Admin Panel</div>
  </div>
  <nav class="sidebar-nav">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="{{ route('admin.categories') }}" class="sidebar-nav-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
      Categories
    </a>
    <a href="{{ route('admin.inquiries') }}" class="sidebar-nav-item {{ request()->routeIs('admin.inquiries') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Inquiries
    </a>
    <a href="{{ route('admin.products') }}" class="sidebar-nav-item {{ request()->routeIs('admin.products') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
      Products
    </a>
    <a href="{{ route('admin.users') }}" class="sidebar-nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Customers
    </a>
    @if(Auth::user()->is_superadmin)
    <a href="{{ route('admin.admins.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.admins*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      Admins
    </a>
    @endif
    <a href="{{ route('home') }}" class="sidebar-nav-item" target="_blank">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      View Site
    </a>
  </nav>
  <div class="sidebar-footer">
    <div class="sidebar-user">Logged in as<strong>{{ Auth::user()->name ?? 'Admin' }}</strong></div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn-logout">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </button>
    </form>
    <button id="themeToggle" class="btn-logout" style="margin-top: 8px;">
      <svg id="themeIcon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      <span id="themeText">Light Mode</span>
    </button>
  </div>
</div>

<!-- ── CONTENT ── -->
<div id="adminContent">
  @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
  @endif

  @yield('admin-content')
</div>

<script>
window.CSRF_TOKEN = '{{ csrf_token() }}';
let toastTimer;
function showToast(msg, type=''){
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = type === 'error' ? 'show error' : 'show';
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.className = '', 3500);
}

// Theme Toggle
const themeToggle = document.getElementById('themeToggle');
const themeIcon = document.getElementById('themeIcon');
const themeText = document.getElementById('themeText');
const sunIcon = `<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>`;
const moonIcon = `<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>`;

function initThemeUI() {
  const isLight = document.documentElement.classList.contains('light-mode');
  if (isLight) {
    themeIcon.innerHTML = moonIcon;
    themeText.textContent = 'Dark Mode';
  } else {
    themeIcon.innerHTML = sunIcon;
    themeText.textContent = 'Light Mode';
  }
}

themeToggle.addEventListener('click', () => {
  const isLight = document.documentElement.classList.toggle('light-mode');
  localStorage.setItem('theme_admin', isLight ? 'light' : 'dark');
  initThemeUI();
});

// Run on load
initThemeUI();

window.addEventListener('storage', (e) => {
  if (e.key === 'theme_admin') {
    if (e.newValue === 'light') document.documentElement.classList.add('light-mode');
    else document.documentElement.classList.remove('light-mode');
    initThemeUI();
  }
});
</script>
@stack('scripts')
</body>
</html>
