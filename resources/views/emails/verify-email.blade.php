<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Your Email Address</title>
<style>
  body {
    background-color: #0D0D0D;
    color: #F0EDE6;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    -webkit-font-smoothing: antialiased;
  }
  .wrapper {
    background-color: #0D0D0D;
    padding: 40px 20px;
    text-align: center;
  }
  .container {
    background-color: #181818;
    border: 1px solid rgba(201, 168, 76, 0.25);
    border-radius: 16px;
    max-width: 480px;
    margin: 0 auto;
    padding: 44px 36px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
  }
  .logo {
    font-size: 26px;
    font-weight: 700;
    font-family: 'Georgia', serif;
    color: #C9A84C;
    letter-spacing: 1.5px;
    margin-bottom: 30px;
    text-transform: uppercase;
  }
  .icon {
    font-size: 40px;
    margin-bottom: 20px;
  }
  .title {
    font-size: 22px;
    font-weight: 600;
    color: #F0EDE6;
    margin-bottom: 12px;
  }
  .subtitle {
    font-size: 14px;
    color: #9C9080;
    line-height: 1.6;
    margin-bottom: 32px;
  }
  .btn-gold {
    background-color: #C9A84C;
    border: 1px solid #C9A84C;
    border-radius: 12px;
    color: #0D0D0D !important;
    font-size: 16px;
    font-weight: 700;
    padding: 16px 32px;
    display: inline-block;
    text-decoration: none;
    margin-bottom: 32px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(201, 168, 76, 0.3);
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  .btn-gold:hover {
    background-color: #DFB75A;
    border-color: #DFB75A;
    box-shadow: 0 6px 20px rgba(201, 168, 76, 0.4);
    transform: translateY(-2px);
  }
  .expiry-notice {
    font-size: 12px;
    color: #5A5248;
    margin-bottom: 30px;
    line-height: 1.5;
  }
  .alternative-link {
    font-size: 12px;
    color: #9C9080;
    word-break: break-all;
    margin-bottom: 30px;
    padding: 12px;
    background-color: #121212;
    border-radius: 8px;
    border: 1px solid rgba(201, 168, 76, 0.1);
  }
  .alternative-link a {
    color: #C9A84C;
    text-decoration: none;
  }
  .footer-divider {
    border-top: 1px solid rgba(201, 168, 76, 0.1);
    margin: 30px 0 20px 0;
  }
  .footer {
    font-size: 11px;
    color: #5A5248;
    line-height: 1.5;
  }
  .footer a {
    color: #C9A84C;
    text-decoration: none;
  }
</style>
</head>
<body>
<div class="wrapper">
  <div class="container">
    <div class="logo">Manas Creations</div>
    <div class="icon">✨</div>
    <div class="title">Verify Your Email</div>
    <div class="subtitle">
      Thank you for choosing Manas Creations. To complete your account registration and ensure you receive order invoices and notifications, please verify your email address.
    </div>
    
    <a href="{{ $url }}" class="btn-gold">Verify Email Address</a>
    
    <div class="expiry-notice">
      ⏰ This verification link is valid for 60 minutes.<br>
      If you did not create an account, please ignore this email.
    </div>

    <div class="alternative-link">
      If the button above does not work, copy and paste this URL into your browser:<br>
      <a href="{{ $url }}">{{ $url }}</a>
    </div>
    
    <div class="footer-divider"></div>
    
    <div class="footer">
      © {{ date('Y') }} <a href="{{ config('app.url') }}">Manas Creations</a>. All rights reserved.<br>
      Premium Acrylic Creations · Thane, Maharashtra, India
    </div>
  </div>
</div>
</body>
</html>
