<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your One-Time Code</title>
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
  .otp-box {
    background: #242424;
    border: 1px dashed rgba(201, 168, 76, 0.4);
    border-radius: 12px;
    color: #C9A84C;
    font-size: 36px;
    font-weight: 700;
    letter-spacing: 6px;
    padding: 16px 24px;
    display: inline-block;
    margin-bottom: 32px;
    font-family: 'Courier New', Courier, monospace;
  }
  .expiry-notice {
    font-size: 12px;
    color: #5A5248;
    margin-bottom: 30px;
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
    <div class="icon">🔐</div>
    <div class="title">Verification Code</div>
    <div class="subtitle">
      Use the one-time passcode (OTP) below to complete your sign-in process. This code is valid for one session only.
    </div>
    
    <div class="otp-box">{{ $otp }}</div>
    
    <div class="expiry-notice">
      ⏰ This verification code will expire in 10 minutes.<br>
      If you did not request this login, please ignore this email.
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
