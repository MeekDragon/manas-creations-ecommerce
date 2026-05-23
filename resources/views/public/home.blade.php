@extends('layouts.app')

@section('title', '✨ Home — Manas Creations')

@section('styles')
<style>
/* ── NAV ── */
#nav {
  position: fixed;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 100%;
  z-index: 1000;
  padding: 16px 5%;
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
#nav.scrolled {
  top: 12px;
  width: 90%;
  max-width: 1100px;
  padding: 0;
}
.nav-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 60px;
  padding: 0 28px;
  border-radius: 16px;
  background: rgba(var(--dark-rgb), 0.6);
  backdrop-filter: blur(20px) saturate(1.8);
  -webkit-backdrop-filter: blur(20px) saturate(1.8);
  border: 1px solid var(--glass-border);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}
.nav-inner::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 200%;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--gold-light), var(--gold), var(--gold-light), transparent);
  animation: navShimmerLine 4s linear infinite;
  pointer-events: none;
}
#nav.scrolled .nav-inner {
  border-radius: 99px;
  background: rgba(var(--dark-rgb), 0.72);
  border-color: rgba(201, 168, 76, 0.35);
  box-shadow: 0 16px 48px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.08);
}

@keyframes navShimmerLine{0%{left:-100%}100%{left:100%}}

/* Logo */
.nav-logo{font-family:'Playfair Display',serif;font-size:22px;font-weight:700;letter-spacing:.5px;position:relative;display:flex;align-items:center;gap:10px}
.nav-logo-icon{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--gold),var(--gold-light));display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:900;font-size:18px;color:var(--dark);box-shadow:0 4px 16px rgba(201,168,76,.3);transition:transform .3s ease,box-shadow .3s ease}
.nav-logo:hover .nav-logo-icon{transform:rotate(-8deg) scale(1.08);box-shadow:0 6px 24px rgba(201,168,76,.5)}
.nav-logo-text{background:linear-gradient(135deg,var(--gold),var(--gold-light),var(--text),var(--gold));background-size:300% 100%;-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;animation:logoShimmer 5s ease-in-out infinite}
.nav-logo-text span{-webkit-text-fill-color:var(--text);font-weight:400;font-size:20px}
@keyframes logoShimmer{0%,100%{background-position:0% 50%}50%{background-position:100% 50%}}

/* Nav Links */
.nav-links{display:flex;gap:4px;align-items:center;background:var(--glass);border-radius:12px;padding:4px}
.nav-links a{font-size:13px;color:var(--text-muted);transition:all .3s ease;font-weight:500;letter-spacing:.3px;padding:8px 18px;border-radius:10px;position:relative;text-transform:uppercase;font-size:11px;letter-spacing:1.2px}
.nav-links a::after{content:'';position:absolute;bottom:4px;left:50%;transform:translateX(-50%);width:0;height:2px;background:linear-gradient(90deg,transparent,var(--gold),transparent);border-radius:2px;transition:width .35s ease}
.nav-links a:hover{color:var(--gold)}
.nav-links a:hover::after{width:60%}
.nav-links a.active{color:var(--gold);background:rgba(201,168,76,.08)}
.nav-links a.active::before{content:'';position:absolute;top:6px;right:8px;width:5px;height:5px;border-radius:50%;background:var(--gold);box-shadow:0 0 8px var(--gold),0 0 16px rgba(201,168,76,.4);animation:dotPulse 2s ease-in-out infinite}
@keyframes dotPulse{0%,100%{opacity:.6;transform:scale(.8)}50%{opacity:1;transform:scale(1.1)}}

/* Nav Actions */
.nav-actions{display:flex;gap:10px;align-items:center}
.nav-wa-btn{font-size:12px;font-weight:600;color:var(--dark);padding:9px 20px;border-radius:10px;display:flex;align-items:center;gap:7px;background:linear-gradient(135deg,var(--gold),var(--gold-light));box-shadow:0 4px 16px rgba(201,168,76,.25);transition:all .3s ease;border:none;cursor:pointer;position:relative;overflow:hidden}
.nav-wa-btn::before{content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:linear-gradient(45deg,transparent 40%,rgba(255,255,255,.2) 50%,transparent 60%);transform:rotate(45deg);transition:left .6s ease}
.nav-wa-btn:hover::before{left:100%}
.nav-wa-btn:hover{transform:translateY(-2px);box-shadow:0 6px 24px rgba(201,168,76,.4)}
.nav-admin-btn{font-size:11px;color:var(--text-dim);background:var(--glass);border:1px solid var(--glass-border);padding:8px 14px;border-radius:8px;cursor:pointer;transition:all .3s ease;letter-spacing:.5px}
.nav-admin-btn:hover{color:var(--gold);border-color:rgba(201,168,76,.2);background:rgba(201,168,76,.06)}

/* Mobile Hamburger */
.nav-hamburger{display:none;width:42px;height:42px;border-radius:10px;background:var(--glass);border:1px solid var(--glass-border);cursor:pointer;flex-direction:column;align-items:center;justify-content:center;gap:5px;transition:all .3s ease;position:relative;z-index:200}
.nav-hamburger:hover{border-color:rgba(201,168,76,.3)}
.nav-hamburger span{display:block;width:20px;height:2px;background:var(--text-muted);border-radius:2px;transition:all .4s cubic-bezier(.68,-.55,.27,1.55)}
.nav-hamburger.active span:nth-child(1){transform:translateY(7px) rotate(45deg);background:var(--gold)}
.nav-hamburger.active span:nth-child(2){opacity:0;transform:scaleX(0)}
.nav-hamburger.active span:nth-child(3){transform:translateY(-7px) rotate(-45deg);background:var(--gold)}

/* Mobile Drawer */
.nav-mobile-drawer{display:none;position:fixed;top:0;right:-100%;width:300px;height:100vh;background:rgba(var(--dark-rgb),.97);backdrop-filter:blur(24px);border-left:1px solid rgba(201,168,76,.15);z-index:150;padding:100px 32px 40px;transition:right .5s cubic-bezier(.4,0,.2,1);flex-direction:column;gap:8px;box-shadow:-20px 0 60px rgba(0,0,0,.5)}
.nav-mobile-drawer.open{right:0}
.nav-mobile-drawer a{font-size:15px;color:var(--text-muted);padding:14px 20px;border-radius:10px;transition:all .3s ease;font-weight:500;border:1px solid transparent}
.nav-mobile-drawer a:hover,.nav-mobile-drawer a.active{color:var(--gold);background:rgba(201,168,76,.06);border-color:rgba(201,168,76,.15)}
.nav-mobile-drawer .drawer-divider{width:100%;height:1px;background:linear-gradient(90deg,transparent,rgba(201,168,76,.2),transparent);margin:12px 0}
.nav-mobile-drawer .drawer-cta{margin-top:auto;display:flex;flex-direction:column;gap:10px}
.nav-mobile-overlay{display:block;pointer-events:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:140;opacity:0;transition:opacity .3s ease}
.nav-mobile-overlay.open{opacity:1;pointer-events:auto}

/* ── HERO ── */
html { scroll-behavior: smooth; }

#hero{min-height:100vh;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;background:radial-gradient(ellipse 80% 60% at 50% 40%,rgba(201,168,76,.07) 0%,transparent 70%)}
.hero-bg-lines{position:absolute;inset:0;background-image:linear-gradient(rgba(201,168,76,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(201,168,76,.04) 1px,transparent 1px);background-size:60px 60px}
.hero-content{text-align:center;z-index:1;max-width:800px;padding:0 20px;animation:fadeUp .9s ease both}
.hero-eyebrow{display:inline-block;font-size:12px;letter-spacing:3px;text-transform:uppercase;color:var(--gold);margin-bottom:24px;border:1px solid rgba(201,168,76,.3);padding:6px 18px;border-radius:100px}
.hero-title{font-family:'Playfair Display',serif;font-size:clamp(42px,7vw,88px);line-height:1.05;font-weight:900;margin-bottom:24px}
.hero-title em{font-style:italic;color:var(--gold)}
.hero-sub{font-size:17px;color:var(--text-muted);max-width:520px;margin:0 auto 44px;line-height:1.7;font-weight:300}
.hero-ctas{display:flex;gap:16px;justify-content:center;flex-wrap:wrap}
.hero-scroll{position:absolute;bottom:36px;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:8px;color:var(--text-dim);font-size:11px;letter-spacing:2px;text-transform:uppercase}
.scroll-line{width:1px;height:40px;background:linear-gradient(to bottom,var(--gold),transparent);animation:scrollPulse 2s ease-in-out infinite}

/* ── SECTIONS ── */
.section{padding:50px 5%}
.section-header{text-align:center;margin-bottom:64px}
.section-eyebrow{font-size:11px;letter-spacing:3px;text-transform:uppercase;color:var(--gold);margin-bottom:16px;display:block}
.section-title{font-family:'Playfair Display',serif;font-size:clamp(32px,4vw,52px);font-weight:700;line-height:1.15}
.section-sub{font-size:15px;color:var(--text-muted);margin-top:12px;max-width:500px;margin-left:auto;margin-right:auto;line-height:1.7}
.divider{width:60px;height:2px;background:linear-gradient(to right,var(--gold),transparent);margin:20px auto 0}

/* ── CATEGORIES ── */
#categories{background:var(--dark2);padding:28px 5%;border-top:1px solid var(--glass-border);border-bottom:1px solid var(--glass-border)}
.cat-list{display:flex;gap:12px;overflow-x:auto;padding-bottom:4px;scrollbar-width:none}
.cat-list::-webkit-scrollbar{display:none}
.cat-pill{flex-shrink:0;padding:8px 20px;border-radius:100px;font-size:13px;font-weight:500;border:1px solid var(--glass-border);background:var(--glass);color:var(--text-muted);transition:var(--tr);cursor:pointer}
.cat-pill.active,.cat-pill:hover{background:var(--gold);color:var(--dark);border-color:var(--gold)}

/* ── PRODUCTS ── */
#products-section{padding:80px 5%}
.products-section-title{font-family:'Playfair Display',serif;font-size:28px;font-weight:700;margin-bottom:32px;padding-bottom:16px;border-bottom:1px solid var(--glass-border);display:flex;align-items:center;gap:12px}
.products-section-title::before{content:'';width:4px;height:28px;background:var(--gold);border-radius:2px;display:inline-block}
.products-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:24px;margin-bottom:60px}
.product-card{background:var(--dark2);border:1px solid var(--glass-border);border-radius:var(--radius);overflow:hidden;transition:var(--tr);cursor:pointer;position:relative}
.product-card:hover{transform:translateY(-6px);border-color:rgba(201,168,76,.4);box-shadow:0 20px 60px rgba(0,0,0,.4)}
.product-img-wrap{width:100%;aspect-ratio:4/3;overflow:hidden;background:var(--dark3)}
.product-img-wrap img{width:100%;height:100%;object-fit:cover;transition:transform .5s ease}
.product-card:hover .product-img-wrap img{transform:scale(1.05)}
.product-img-placeholder{width:100%;aspect-ratio:4/3;background:var(--dark3);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;color:var(--text-dim)}
.product-body{padding:18px 20px 20px}
.product-badge{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:var(--gold);margin-bottom:8px;display:block}
.product-name{font-family:'Playfair Display',serif;font-size:18px;font-weight:600;margin-bottom:6px;line-height:1.3}
.product-desc{font-size:13px;color:var(--text-muted);line-height:1.6;margin-bottom:14px}
.product-price{font-size:20px;font-weight:600;color:var(--gold);margin-bottom:14px}
.product-actions{display:flex;gap:8px}
.btn-enquire{flex:1;background:var(--gold);color:var(--dark);padding:10px;border-radius:8px;font-size:13px;font-weight:600;transition:var(--tr);border:none;cursor:pointer}
.btn-enquire:hover{background:var(--gold-light)}
.btn-whatsapp{width:40px;height:40px;border-radius:8px;background:rgba(37,211,102,.15);border:1px solid rgba(37,211,102,.3);display:flex;align-items:center;justify-content:center;transition:var(--tr);flex-shrink:0}
.btn-whatsapp:hover{background:rgba(37,211,102,.25)}

/* ── WHY US ── */
#why{background:var(--dark2);min-height:100vh;display:flex;flex-direction:column;justify-content:center}
.features-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:24px}
.feature-card{background:var(--dark3);border:1px solid var(--glass-border);border-radius:var(--radius);padding:32px 28px;transition:var(--tr)}
.feature-card:hover{border-color:rgba(201,168,76,.35);transform:translateY(-3px)}
.feature-icon{width:52px;height:52px;border-radius:12px;background:rgba(201,168,76,.1);display:flex;align-items:center;justify-content:center;margin-bottom:20px;font-size:22px}
.feature-title{font-size:16px;font-weight:600;margin-bottom:8px}
.feature-desc{font-size:13px;color:var(--text-muted);line-height:1.6}

/* ── CONTACT ── */
#contact{background:var(--dark);padding-top:72px}
.contact-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;max-width:960px;margin:0 auto}
.contact-info-card{background:var(--dark2);border:1px solid var(--glass-border);border-radius:var(--radius);padding:36px;display:flex;flex-direction:column;gap:28px}
.contact-item{display:flex;gap:16px;align-items:flex-start}
.contact-icon{width:44px;height:44px;border-radius:10px;background:rgba(201,168,76,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px}
.contact-label{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--text-dim);margin-bottom:4px}
.contact-val{font-size:15px;color:var(--text);font-weight:500}
.contact-val a{color:var(--gold);transition:opacity .2s}
.contact-val a:hover{opacity:.75}
.whatsapp-cta{display:flex;align-items:center;gap:12px;padding:16px 20px;background:rgba(37,211,102,.08);border:1px solid rgba(37,211,102,.2);border-radius:10px;color:var(--text);font-size:14px;font-weight:500;transition:var(--tr)}
.whatsapp-cta:hover{background:rgba(37,211,102,.15)}
.query-form-card{background:var(--dark2);border:1px solid var(--glass-border);border-radius:var(--radius);padding:36px}
.form-title{font-family:'Playfair Display',serif;font-size:22px;font-weight:700;margin-bottom:24px}
.form-group{margin-bottom:18px}
.form-group label{display:block;font-size:12px;letter-spacing:1.5px;text-transform:uppercase;color:var(--text-muted);margin-bottom:8px;font-weight:500}
.form-group input,.form-group textarea,.form-group select{width:100%;background:var(--dark3);border:1px solid var(--glass-border);border-radius:8px;padding:12px 16px;color:var(--text);font-size:14px;transition:border-color .2s}
.form-group input:focus,.form-group textarea:focus,.form-group select:focus{outline:none;border-color:var(--gold)}
.form-group textarea{resize:vertical;min-height:110px}
.form-group select option{background:var(--dark3)}
.form-submit{width:100%;padding:14px;font-size:15px}
.form-success{text-align:center;padding:18px;background:rgba(201,168,76,.08);border:1px solid rgba(201,168,76,.2);border-radius:8px;color:var(--gold);font-size:14px;margin-top:16px}
.field-error{font-size:12px;color:#E24B4A;margin-top:4px}

/* ── FOOTER ── */
footer{background:var(--dark2);border-top:1px solid var(--glass-border);padding:48px 5% 28px}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:40px;margin-bottom:40px}
.footer-brand{font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:var(--gold);margin-bottom:12px}
.footer-desc{font-size:13px;color:var(--text-muted);line-height:1.7;max-width:280px}
.footer-col h4{font-size:12px;letter-spacing:2px;text-transform:uppercase;color:var(--text-dim);margin-bottom:16px}
.footer-col a,.footer-col p{display:block;font-size:13px;color:var(--text-muted);margin-bottom:8px;transition:color .2s}
.footer-col a:hover{color:var(--gold)}
.footer-bottom{border-top:1px solid var(--glass-border);padding-top:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
.footer-copy{font-size:12px;color:var(--text-dim)}

/* ── MODALS ── */
#modal-overlay{position:fixed;inset:0;z-index:300;background:rgba(0,0,0,.7);display:flex;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(4px)}
.modal{background:var(--dark2);border:1px solid var(--glass-border);border-radius:var(--radius);padding:32px;max-width:500px;width:100%;max-height:90vh;overflow-y:auto;transition:max-width 0.3s cubic-bezier(.4,0,.2,1)}
.modal.modal-detailed{max-width:800px;width:100%;padding:28px}
.modal-title{font-family:'Playfair Display',serif;font-size:20px;font-weight:700;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between}
.modal-close{cursor:pointer;color:var(--text-muted);font-size:20px;transition:color .2s;background:none;border:none}
.modal-close:hover{color:var(--gold)}
.modal-actions{display:flex;gap:12px;margin-top:20px}
.product-modal-img{width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:8px;margin-bottom:20px;background:var(--dark3);display:block}
.product-modal-img-new{width:100%;aspect-ratio:1/1;object-fit:cover;border-radius:8px;background:var(--dark3);border:1px solid var(--glass-border);display:block}
.product-modal-thumbs{display:flex;gap:8px;margin-bottom:20px;overflow-x:auto}
.product-modal-thumb{width:64px;height:64px;object-fit:cover;border-radius:6px;cursor:pointer;border:2px solid transparent;flex-shrink:0;transition:border-color .2s}
.product-modal-thumb.active{border-color:var(--gold)}
.modal-price-wrap { display:flex; align-items:center; gap:10px; margin-bottom:16px; }
.modal-price { font-size:22px; font-weight:700; color:var(--text); }
.modal-mrp { font-size:14px; color:var(--text-dim); text-decoration:line-through; font-weight:400; }
.modal-discount { font-size:12px; font-weight:700; color:#25d366; }
.rating-wrap { display:flex; align-items:center; gap:6px; margin-bottom:12px; }
.star-rating { color:#f59e0b; font-size:13px; font-weight:600; background:rgba(255,255,255,0.05); padding:2px 6px; border-radius:4px; display:inline-flex; align-items:center; gap:4px; }
.reviews-link { font-size:13px; color:var(--text-muted); }
.suggestions-title { font-size:15px; font-weight:600; margin-top:24px; margin-bottom:12px; padding-top:20px; border-top:1px solid var(--glass-border); font-family:'Playfair Display',serif; }
.suggestions-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(120px,1fr)); gap:12px; }
.suggestion-card { background:var(--dark3); border-radius:8px; overflow:hidden; border:1px solid var(--glass-border); cursor:pointer; display:flex; flex-direction:column; transition:var(--tr); }
.suggestion-card:hover { border-color:var(--gold); transform:translateY(-2px); }
.suggestion-img { width:100%; aspect-ratio:4/3; object-fit:cover; background:var(--dark2); }
.suggestion-info { padding:10px; }
.suggestion-name { font-size:13px; font-weight:600; line-height:1.2; margin-bottom:4px; }
.suggestion-price { font-size:12px; color:var(--text); font-weight:600; }
.modal-detailed-grid{display:grid;grid-template-columns:1.1fr 0.9fr;gap:32px;align-items:start}
.modal-detailed-left{display:flex;flex-direction:column}
.modal-detailed-right{display:flex;flex-direction:column;height:100%}

@media(max-width:768px){
  #nav {
    padding: 12px 16px;
  }
  #nav.scrolled {
    top: 8px;
    width: 94%;
  }
  .nav-links{display:none!important}
  .nav-hamburger{display:flex}
  .nav-mobile-drawer{display:flex}
  .nav-inner{padding:0 16px}
  .contact-grid{grid-template-columns:1fr}
  .footer-grid{grid-template-columns:1fr}

  /* Compress mobile header actions: hide WhatsApp, admin links, and logout forms in top-bar */
  .nav-actions a, .nav-actions form {
    display: none !important;
  }

  /* Sideways Category Product Scroll */
  .products-grid {
    display: flex !important;
    overflow-x: auto !important;
    scroll-snap-type: x mandatory !important;
    gap: 16px !important;
    padding: 4px 4px 16px 4px !important;
    margin-bottom: 30px !important;
    scrollbar-width: none !important; /* Hide scrollbar Firefox */
    -webkit-overflow-scrolling: touch !important; /* momentum scroll iOS */
  }
  .products-grid::-webkit-scrollbar {
    display: none !important; /* Hide scrollbar Chrome/Safari */
  }
  .products-grid .product-card {
    flex: 0 0 270px !important;
    scroll-snap-align: start !important;
    margin: 0 !important;
  }
}

@media(max-width:480px){
  .nav-logo-text {
    font-size: 16px !important;
  }
  .nav-logo-text span {
    font-size: 15px !important;
  }
  .nav-logo-icon {
    width: 30px !important;
    height: 30px !important;
    font-size: 14px !important;
  }
  .nav-logo {
    gap: 6px !important;
  }
}
</style>
@endsection

@section('content')

@auth
  @if(!Auth::user()->hasVerifiedEmail())
    <div style="background:var(--gold);color:var(--dark);padding:10px 5%;text-align:center;font-size:13px;font-weight:600;display:flex;justify-content:center;align-items:center;gap:12px;position:relative;z-index:200;flex-wrap:wrap;">
      @if(\Illuminate\Support\Str::startsWith(Auth::user()->email, 'mobile_'))
        <span>Please provide your email address to receive order updates and invoices:</span>
        <form method="POST" action="{{ route('verification.send.email') }}" style="margin:0;display:flex;align-items:center;gap:8px;">
          @csrf
          <input type="email" name="email" placeholder="yourname@email.com" required style="background:var(--dark);color:#fff;border:1px solid var(--gold);padding:6px 12px;border-radius:6px;font-size:12px;outline:none;">
          <button type="submit" style="background:var(--dark);color:var(--gold);border:none;padding:6px 12px;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;">Verify & Save</button>
        </form>
      @else
        <span>Please verify your email to receive order updates and invoices ({{ Auth::user()->email }}).</span>
        <form method="POST" action="{{ route('verification.send.email') }}" style="margin:0;">
          @csrf
          <button type="submit" style="background:var(--dark);color:var(--gold);border:none;padding:6px 12px;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;">Send Verification Link</button>
        </form>
      @endif
    </div>
  @endif
@endauth

<!-- ══ NAV ══ -->
<nav id="nav">
  <div class="nav-inner">
    <a href="#hero" class="nav-logo">
      <div class="nav-logo-icon">M</div>
      <div class="nav-logo-text">Manas <span>Creations</span></div>
    </a>
    <div class="nav-links">
      <a href="#hero" class="active" data-section="hero">Home</a>
      <a href="#products-section" data-section="products-section">All Products</a>
      <a href="#why" data-section="why">About Us</a>
      <a href="#contact" data-section="contact">Contact</a>
    </div>
    <div class="nav-actions">
      <a href="https://wa.me/918928202040" target="_blank" class="nav-wa-btn">
        @include('partials.wa-icon')WhatsApp
      </a>
      <button onclick="toggleTheme()" class="nav-admin-btn" style="padding:8px 10px;background:transparent;border-color:transparent" title="Toggle Theme" aria-label="Toggle Theme">
        <svg class="theme-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;display:block"></svg>
      </button>
      @auth
        @if(Auth::user()->is_admin || Auth::user()->is_superadmin)
          <a href="{{ route('admin.dashboard') }}" class="nav-admin-btn">Admin ↗</a>
        @endif
        <form action="{{ route('logout', [], false) }}" method="POST" style="display:inline">
          @csrf
          <button type="submit" class="nav-admin-btn" style="background:transparent;margin-left:4px">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="nav-admin-btn" style="background:transparent;border-color:transparent">Login</a>
        <a href="{{ route('register') }}" class="nav-admin-btn" style="background:var(--gold);color:var(--dark);border-color:var(--gold)">Sign Up</a>
      @endauth
      <button class="nav-hamburger" onclick="toggleMobileNav()" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</nav>

<!-- Mobile Drawer -->
<div class="nav-mobile-overlay" id="navOverlay" onclick="toggleMobileNav()"></div>
<div class="nav-mobile-drawer" id="navDrawer">
  <a href="#hero" onclick="toggleMobileNav()" class="active" data-section="hero">Home</a>
  <a href="#products-section" onclick="toggleMobileNav()" data-section="products-section">All Products</a>
  <a href="#why" onclick="toggleMobileNav()" data-section="why">About Us</a>
  <a href="#contact" onclick="toggleMobileNav()" data-section="contact">Contact</a>
  <div class="drawer-divider"></div>
  <div class="drawer-cta">
    <a href="https://wa.me/918928202040" target="_blank" class="nav-wa-btn" style="justify-content:center">
      @include('partials.wa-icon')Chat on WhatsApp
    </a>
    <button onclick="toggleTheme()" class="nav-admin-btn" style="text-align:center;display:flex;align-items:center;justify-content:center;gap:8px" title="Toggle Theme">
      <svg class="theme-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;display:block"></svg> Toggle Theme
    </button>
    @auth
      @if(Auth::user()->is_admin)
        <a href="{{ route('admin.dashboard') }}" class="nav-admin-btn" style="text-align:center">Admin Panel ↗</a>
      @endif
      <form action="{{ route('logout', [], false) }}" method="POST" style="display:flex;flex-direction:column">
        @csrf
        <button type="submit" class="nav-admin-btn" style="width:100%">Logout</button>
      </form>
    @else
      <a href="{{ route('login') }}" class="nav-admin-btn" style="text-align:center;background:transparent;border-color:rgba(255,255,255,.1)">Login</a>
      <a href="{{ route('register') }}" class="nav-admin-btn" style="text-align:center;background:var(--gold);color:var(--dark);border-color:var(--gold)">Sign Up</a>
    @endauth
  </div>
</div>

<!-- ══ HERO ══ -->
<section id="hero">
  <div class="hero-bg-lines"></div>
  <div class="hero-content">
    <span class="hero-eyebrow">Premium Acrylic Creations</span>
    <h1 class="hero-title">Crafted in <em>Acrylic</em>,<br>Made to Last</h1>
    <p class="hero-sub">Custom keychains, trophies, nameplates, clocks, awards &amp; more — all precision-crafted in premium acrylic for gifts, corporates &amp; personal use.</p>
    <div class="hero-ctas">
      <button class="btn-gold" onclick="document.getElementById('products-section').scrollIntoView({behavior:'smooth'})">Explore Products</button>
      <a href="https://wa.me/918928202040?text=Hi%2C%20I%20want%20to%20enquire%20about%20your%20acrylic%20products" target="_blank" class="btn-outline">Get a Quote</a>
    </div>
  </div>
  <div class="hero-scroll"><div class="scroll-line"></div><span>Scroll</span></div>
</section>

<!-- ══ CATEGORY PILLS ══ -->
<div id="categories">
  <div class="cat-list">
    <div class="cat-pill active" onclick="filterProducts('All',this)">All</div>
    @foreach($categories as $cat)
      <div class="cat-pill" onclick="filterProducts('{{ $cat->name }}',this)">{{ $cat->name }}</div>
    @endforeach
  </div>
</div>

<!-- ══ PRODUCTS ══ -->
<section id="products-section">
  @forelse($productsByCategory as $cat => $catProducts)
    <div class="prod-section" data-cat="{{ $cat }}" style="padding:60px 5% 20px">
      <div class="products-section-title">{{ $cat }}</div>
      <div class="products-grid">
        @foreach($catProducts as $product)
          @include('partials.product-card', ['product' => $product])
        @endforeach
      </div>
    </div>
  @empty
    <div style="padding:60px 5%;text-align:center;color:var(--text-muted)">No products yet — check back soon!</div>
  @endforelse
</section>

<!-- ══ WHY US ══ -->
<section id="why" class="section">
  <div class="section-header">
    <span class="section-eyebrow">Our Promise</span>
    <h2 class="section-title">Why Choose <span class="gold">Manas Creations</span></h2>
    <div class="divider"></div>
  </div>
  <div class="features-grid">
    <div class="feature-card"><div class="feature-icon">✨</div><div class="feature-title">Premium Quality Acrylic</div><div class="feature-desc">We use only the finest grade acrylic sheets, ensuring crystal clarity, durability, and long-lasting shine in every product.</div></div>
    <div class="feature-card"><div class="feature-icon">🎨</div><div class="feature-title">Fully Customizable</div><div class="feature-desc">Every item can be personalized — names, logos, colours, sizes. Tell us your vision and we'll bring it to life.</div></div>
    <div class="feature-card"><div class="feature-icon">⚡</div><div class="feature-title">Fast Turnaround</div><div class="feature-desc">Quick production timelines without compromising quality. Bulk orders for corporates and events handled efficiently.</div></div>
    <div class="feature-card"><div class="feature-icon">🤝</div><div class="feature-title">Trusted by Hundreds</div><div class="feature-desc">From individuals to large corporations, we've delivered thousands of products with consistent satisfaction.</div></div>
  </div>
</section>

<!-- ══ CONTACT ══ -->
<section id="contact" class="section">
  <div class="section-header">
    <span class="section-eyebrow">Get in Touch</span>
    <h2 class="section-title">Contact <span class="gold">Us</span></h2>
    <p class="section-sub">Send us your query or reach out directly. We respond within 24 hours.</p>
    <div class="divider"></div>
  </div>
  <div class="contact-grid">
    <div style="display:flex;flex-direction:column;gap:20px">
      <div class="contact-info-card">
        <div class="contact-item"><div class="contact-icon">📍</div><div><div class="contact-label">Location</div><div class="contact-val"><a href="https://maps.app.goo.gl/2j3nGvQV3hk1D3WS7" target="_blank">Thane, Maharashtra, India</a></div></div></div>
        <div class="contact-item"><div class="contact-icon">📞</div><div><div class="contact-label">Phone</div><div class="contact-val"><a href="tel:+918928202040">+91 89282 02040</a></div></div></div>
        <div class="contact-item"><div class="contact-icon">📧</div><div><div class="contact-label">Email</div><div class="contact-val"><a href="mailto:manascreationsofficial@gmail.com">manascreationsofficial@gmail.com</a></div></div></div>
        <div class="contact-item"><div class="contact-icon">🌐</div><div><div class="contact-label">Website</div><div class="contact-val"><a href="https://manascreations.in" target="_blank">manascreations.in</a></div></div></div>
        <div class="contact-item"><div class="contact-icon">📸</div><div><div class="contact-label">Instagram</div><div class="contact-val"><a href="https://www.instagram.com/manascreationofficial?igsh=MTllbnhpam1mazU1MA==" target="_blank">@manascreationofficial</a></div></div></div>
        <a href="https://wa.me/918928202040?text=Hi%2C%20I%27d%20like%20to%20enquire%20about%20your%20acrylic%20products" target="_blank" class="whatsapp-cta">
          @include('partials.wa-icon', ['size' => 22])Chat on WhatsApp — 89282 02040
        </a>
      </div>
      <div style="background:var(--dark2);border:1px solid var(--glass-border);border-radius:var(--radius);padding:24px;text-align:center;color:var(--text-muted)">
        <div style="font-size:28px;margin-bottom:10px">📍</div>
        <p style="font-size:14px;margin-bottom:10px">Thane, Maharashtra, India</p>
        <a href="https://maps.app.goo.gl/2j3nGvQV3hk1D3WS7" target="_blank" style="color:var(--gold);font-size:13px">View on Google Maps →</a>
      </div>
    </div>

    <!-- Contact Form -->
    <div class="query-form-card">
      <div class="form-title">Send a Query</div>
      @if(session('success'))
        <div class="form-success">{{ session('success') }}</div>
      @endif
      @auth
        @if(Auth::user()->isVerified())
          <div style="font-size:13px;color:var(--text-muted);line-height:1.6;margin-bottom:18px">
            Signed in as <strong style="color:var(--gold)">{{ Auth::user()->name }}</strong>.
          </div>
          <form method="POST" action="{{ route('inquiry.store') }}" id="contactForm">
            @csrf
            <div class="form-group">
              <label>Mobile Number</label>
              <input type="tel" name="contact" placeholder="+91 XXXXX XXXXX" value="{{ old('contact', Auth::user()->mobile) }}" required>
              @error('contact')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label>Product Category</label>
              <select name="category">
                <option value="">Select category...</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->name }}" {{ old('category') === $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Your Message</label>
              <textarea name="message" placeholder="Describe your requirement, quantity, customization needed..." required>{{ old('message') }}</textarea>
              @error('message')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn-gold form-submit">Send Query</button>
          </form>
        @else
          <div style="font-size:14px;color:var(--text-muted);line-height:1.7">
            Please verify your account before sending a query.
          </div>
          <a href="{{ route('verification.notice') }}" class="btn-gold form-submit" style="text-align:center;margin-top:18px">Verify Account</a>
        @endif
      @else
        <div style="font-size:14px;color:var(--text-muted);line-height:1.7">
          Please login or create an account to send a query. This keeps inquiries tied to a verified email address.
        </div>
        <div style="display:flex;gap:12px;margin-top:18px;flex-wrap:wrap">
          <a href="{{ route('login') }}" class="btn-gold" style="flex:1;text-align:center">Login</a>
          <a href="{{ route('register') }}" class="btn-outline" style="flex:1;text-align:center">Sign Up</a>
        </div>
      @endauth
    </div>
  </div>
</section>

<!-- ══ FOOTER ══ -->
<footer>
  <div class="footer-grid">
    <div>
      <div class="footer-brand">Manas Creations</div>
      <p class="footer-desc">Premium acrylic products crafted with precision and passion. Custom orders welcome for individuals, corporates, and events across India.</p>
      <p style="font-size:13px;color:var(--text-dim);margin-top:12px">manascreations.in</p>
    </div>
    <div class="footer-col">
      <h4>Products</h4>
      @foreach($categories as $cat)
        <a href="#products-section">{{ $cat->name }}</a>
      @endforeach
    </div>
    <div class="footer-col">
      <h4>Contact</h4>
      <p>+91 89282 02040</p>
      <a href="mailto:manascreationsofficial@gmail.com">manascreationsofficial@gmail.com</a>
      <p>Thane, Maharashtra, India</p>
      <a href="https://wa.me/918928202040" target="_blank" style="color:var(--gold);margin-bottom:8px">WhatsApp Us →</a>
      <a href="https://www.instagram.com/manascreationofficial?igsh=MTllbnhpam1mazU1MA==" target="_blank" style="color:var(--gold)">Instagram →</a>
    </div>
  </div>
  <div class="footer-bottom">
    <span class="footer-copy">© {{ date('Y') }} Manas Creations. All rights reserved.</span>
    <span class="footer-copy">Made with ❤ in India</span>
  </div>
</footer>

<!-- ══ PRODUCT DETAIL MODAL ══ -->
<div id="modal-overlay" class="hidden" onclick="closeModalOuter(event)">
  <div class="modal" id="modal-box">
    <div class="modal-title">
      <span id="modal-title-text"></span>
      <button class="modal-close" onclick="closeModal()">✕</button>
    </div>
    <div id="modal-body"></div>
    <div class="modal-actions" id="modal-actions"></div>
  </div>
</div>

@endsection

@push('scripts')
@php
  $authUserPayload = Auth::check() ? [
    'name' => Auth::user()->name,
    'email' => Auth::user()->email,
    'mobile' => Auth::user()->mobile,
    'verified' => Auth::user()->isVerified(),
  ] : null;
@endphp
<script>
// Embed products data from server
const PRODUCTS = @json($products);
const AUTH_USER = @json($authUserPayload);
window.AUTH_USER = AUTH_USER;

// ── Nav scroll effect ──
const nav = document.getElementById('nav');
const sections = document.querySelectorAll('section[id], #hero');
const navLinks = document.querySelectorAll('.nav-links a, .nav-mobile-drawer > a');

window.addEventListener('scroll', () => {
  // Shrink on scroll
  if (window.scrollY > 40) { nav.classList.add('scrolled'); }
  else { nav.classList.remove('scrolled'); }

  // Active section highlight
  let current = '';
  sections.forEach(sec => {
    const top = sec.offsetTop - 120;
    if (window.scrollY >= top) current = sec.getAttribute('id');
  });
  navLinks.forEach(a => {
    a.classList.remove('active');
    if (a.dataset.section === current || a.getAttribute('href') === '#' + current) a.classList.add('active');
  });
});

// ── Mobile nav ──
function toggleMobileNav() {
  const ham = document.querySelector('.nav-hamburger');
  const drawer = document.getElementById('navDrawer');
  const overlay = document.getElementById('navOverlay');
  ham.classList.toggle('active');
  drawer.classList.toggle('open');
  overlay.classList.toggle('open');
  document.body.style.overflow = drawer.classList.contains('open') ? 'hidden' : '';
}

// ── Category filter ──
function filterProducts(cat, el) {
  document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('.prod-section').forEach(s => {
    s.style.display = (cat === 'All' || s.dataset.cat === cat) ? 'block' : 'none';
  });
  document.getElementById('products-section').scrollIntoView({behavior:'smooth'});
}

// ── Modal ──
function openModal(){
  document.getElementById('modal-overlay').classList.remove('hidden');
}
function closeModal(){
  document.getElementById('modal-overlay').classList.add('hidden');
  document.getElementById('modal-box').classList.remove('modal-detailed');
  document.getElementById('modal-actions').style.display = 'flex';
}
function closeModalOuter(e){ if(e.target === document.getElementById('modal-overlay')) closeModal() }

function openProductModal(pid) {
  const p = PRODUCTS.find(x => x.id == pid);
  if (!p) return;
  
  // Set the modal layout style class
  document.getElementById('modal-box').classList.add('modal-detailed');
  document.getElementById('modal-actions').style.display = 'none'; // hide the external actions bar
  
  const imgs = p.images || [];
  const mainImgHtml = imgs.length
    ? `<img id="pModalImg" src="${storageUrl(imgs[0])}" class="product-modal-img-new" alt="${p.name}">`
    : `<div class="product-modal-img-new" style="display:flex;align-items:center;justify-content:center;color:var(--text-dim)">No image</div>`;
  const thumbsHtml = imgs.length > 1
    ? `<div class="product-modal-thumbs" style="margin-top:12px;">${imgs.map((img, i) => `<img src="${storageUrl(img)}" class="product-modal-thumb${i===0?' active':''}" onclick="switchModalImg(this,'${storageUrl(img)}')" alt="">`).join('')}</div>`
    : '';
  let priceHtml = '';
  
  const ratingHtml = `<div class="rating-wrap" style="margin-bottom:8px;">
    <div class="star-rating">${Number(p.rating || 4.5).toFixed(1)} <span style="font-size:11px">★</span></div>
    <span class="reviews-link" style="margin-left:8px;">${p.reviews_count || 0} Ratings & Reviews</span>
  </div>`;
  
  const suggestions = PRODUCTS.filter(x => x.category_id === p.category_id && x.id !== p.id).slice(0, 4);
  let suggHtml = '';
  if (suggestions.length > 0) {
    suggHtml = `<div class="suggestions-title" style="grid-column: span 2; margin-top:24px;">Similar Products</div>
                <div class="suggestions-grid" style="grid-column: span 2;">`;
    suggestions.forEach(s => {
      const sImg = (s.images && s.images.length > 0) ? storageUrl(s.images[0]) : '';
      suggHtml += `
        <div class="suggestion-card" onclick="openProductModal(${s.id})">
          ${sImg ? `<img src="${sImg}" class="suggestion-img">` : `<div class="suggestion-img" style="display:flex;align-items:center;justify-content:center;font-size:10px;color:var(--text-dim)">No img</div>`}
          <div class="suggestion-info">
            <div class="suggestion-name">${s.name}</div>
          </div>
        </div>`;
    });
    suggHtml += `</div>`;
  }
  
  const wa = encodeURIComponent(`Hi, I'm interested in: ${p.name}`);
  document.getElementById('modal-title-text').textContent = p.category ? p.category.name : '';
  
  document.getElementById('modal-body').innerHTML = `
    <div class="modal-detailed-grid">
      <!-- Left Column: Media -->
      <div class="modal-detailed-left">
        ${mainImgHtml}
        ${thumbsHtml}
      </div>
      
      <!-- Right Column: Details & Actions -->
      <div class="modal-detailed-right">
        <h3 style="font-family:'Playfair Display',serif; font-size:24px; font-weight:700; margin:0 0 4px; line-height:1.2; color:var(--text);">${p.name}</h3>
        ${p.tagline ? `<div style="font-size:14px; color:var(--text-dim); margin-bottom:8px;">${p.tagline}</div>` : ''}
        
        ${ratingHtml}
        
        ${priceHtml}
        
        <p style="font-size:13px; color:var(--text-muted); line-height:1.7; margin-bottom:16px;">${p.description||''}</p>
        
        <div class="modal-actions" style="margin-top:auto; display:flex; gap:12px; width:100%;">
          <button class="btn-gold" style="flex:1; padding:12px;" onclick="openQueryModal(${p.id})">Enquire Now</button>
          <a href="https://wa.me/918928202040?text=${wa}" target="_blank" class="btn-whatsapp" style="display:flex;align-items:center;justify-content:center;padding:12px;border-radius:8px;background:rgba(37,211,102,.15);border:1px solid rgba(37,211,102,.3);transition:var(--tr);width:48px;height:48px;box-sizing:border-box;flex-shrink:0;">
            @include('partials.wa-icon', ['size'=>20])
          </a>
        </div>
      </div>
      
      <!-- Suggestions spanning 2 columns -->
      ${suggHtml}
    </div>
  `;
  
  window._currentModalProduct = p;
  window._currentModalVariantStr = 'Standard';
  
  openModal();
}

function selectVariant(btn, pName, size) {
  document.querySelectorAll('#modalSizeSelector .size-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  
  window._currentModalVariantStr = size;
  
  const waBtn = document.querySelector('#modal-actions .btn-whatsapp');
  if (waBtn) {
    waBtn.href = "https://wa.me/918928202040?text=" + encodeURIComponent(`Hi, I'm interested in: ${pName} (Size: ${size})`);
  }
}

function switchModalImg(el, src) {
  document.getElementById('pModalImg').src = src;
  document.querySelectorAll('.product-modal-thumb').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
}

function openQueryModal(pid) {
  const p = pid ? PRODUCTS.find(x => x.id == pid) : null;
  const sizeStr = (p && window._currentModalVariantStr) ? ` (Size: ${window._currentModalVariantStr})` : '';
  
  document.getElementById('modal-title-text').textContent = 'Send Query';
  if (!AUTH_USER) {
    document.getElementById('modal-body').innerHTML = `
      ${p ? `<p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">Enquiring about: <strong style="color:var(--gold)">${p.name}${sizeStr}</strong></p>` : ''}
      <p style="font-size:14px;color:var(--text-muted);line-height:1.7">Please login or create an account before sending a query. Your email will be verified with a secure link.</p>`;
    document.getElementById('modal-actions').innerHTML = `
      <a href="{{ route('login') }}" class="btn-gold" style="flex:1;text-align:center">Login</a>
      <a href="{{ route('register') }}" class="btn-outline" style="flex:1;text-align:center">Sign Up</a>`;
    openModal();
    return;
  }
  if (!AUTH_USER.verified) {
    document.getElementById('modal-body').innerHTML = `
      ${p ? `<p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">Enquiring about: <strong style="color:var(--gold)">${p.name}${sizeStr}</strong></p>` : ''}
      <p style="font-size:14px;color:var(--text-muted);line-height:1.7">Please verify your account before sending a query.</p>`;
    document.getElementById('modal-actions').innerHTML = `
      <a href="{{ route('verification.notice') }}" class="btn-gold" style="flex:1;text-align:center">Verify Account</a>
      <button class="btn-outline" onclick="closeModal()">Cancel</button>`;
    openModal();
    return;
  }
  document.getElementById('modal-body').innerHTML = `
    ${p ? `<p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">Enquiring about: <strong style="color:var(--gold)">${p.name}${sizeStr}</strong></p>` : ''}
    <p style="font-size:13px;color:var(--text-muted);line-height:1.6;margin-bottom:16px">Signed in as <strong style="color:var(--gold)">${AUTH_USER.name}</strong>.</p>
    <div class="form-group"><label>Mobile Number</label><input type="tel" id="mqContact" value="${AUTH_USER.mobile || ''}" placeholder="+91 XXXXX XXXXX"></div>
    <div class="form-group"><label>Message</label><textarea id="mqMsg" placeholder="Your requirement...">${p ? `I am interested in ${p.name}${sizeStr}. Please share more details.` : ''}</textarea></div>`;
  document.getElementById('modal-actions').innerHTML = `
    <button class="btn-gold" style="flex:1" onclick="submitModalQuery(${pid||0})">Send Query →</button>
    <button class="btn-outline" onclick="closeModal()">Cancel</button>`;
  openModal();
}

async function submitModalQuery(pid) {
  const contact = document.getElementById('mqContact').value.trim();
  const msg     = document.getElementById('mqMsg').value.trim();
  if (!contact || !msg) { showToast('Please fill mobile number and message.'); return; }
  const p = pid ? PRODUCTS.find(x => x.id == pid) : null;
  try {
    const res = await fetch('{{ route("inquiry.store", [], false) }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': window.CSRF_TOKEN },
      body: JSON.stringify({ contact, category: (p && p.category) ? p.category.name : 'General', message: msg, product: p?.name || '' }),
    });
    if (!res.ok) throw new Error();
    closeModal();
    showToast("✓ Query sent! We'll contact you soon.");
  } catch(e) {
    showToast('Failed to send query. Please WhatsApp us directly.', 'error');
  }
}

function storageUrl(path) {
  if (!path) return '';
  if (path.startsWith('http')) return path;
  return '/storage/' + path;
}

// ── Global WhatsApp Click Gate ──
document.addEventListener('click', function(e) {
  // Capture clicks on links that go to wa.me (or elements within them)
  const anchor = e.target.closest('a[href*="wa.me"]');
  if (anchor) {
    if (!window.AUTH_USER) {
      e.preventDefault();
      e.stopPropagation();
      
      document.getElementById('modal-title-text').textContent = 'Sign In Required';
      document.getElementById('modal-body').innerHTML = `
        <div style="text-align:center;padding:12px 0">
          <div style="font-size:42px;margin-bottom:16px">💬</div>
          <p style="font-size:14px;color:var(--text-muted);line-height:1.7;margin-bottom:8px">
            Please log in or sign up before messaging us on WhatsApp.
          </p>
          <span style="font-size:11px;color:var(--text-dim)">This helps us prevent spam and maintain high support standards.</span>
        </div>
      `;
      document.getElementById('modal-actions').innerHTML = `
        <a href="{{ route('login') }}" class="btn-gold" style="flex:1;text-align:center">Sign In</a>
        <a href="{{ route('register') }}" class="btn-outline" style="flex:1;text-align:center">Sign Up</a>
      `;
      openModal();
    } else if (!window.AUTH_USER.verified) {
      e.preventDefault();
      e.stopPropagation();
      
      document.getElementById('modal-title-text').textContent = 'Verification Required';
      document.getElementById('modal-body').innerHTML = `
        <div style="text-align:center;padding:12px 0">
          <div style="font-size:42px;margin-bottom:16px">🔒</div>
          <p style="font-size:14px;color:var(--text-muted);line-height:1.7;margin-bottom:8px">
            Please verify your account before contacting us on WhatsApp.
          </p>
          <span style="font-size:11px;color:var(--text-dim)">Click the verification link sent to your email to unlock full features.</span>
        </div>
      `;
      document.getElementById('modal-actions').innerHTML = `
        <a href="{{ route('verification.notice') }}" class="btn-gold" style="flex:1;text-align:center">Verify Now</a>
        <button class="btn-outline" style="flex:1" onclick="closeModal()">Cancel</button>
      `;
      openModal();
    }
  }
}, true); // useCapture = true to intercept before other click handlers
</script>
@endpush
