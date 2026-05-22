<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inquiry Answered</title>
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
  }
  .container {
    background-color: #181818;
    border: 1px solid rgba(201, 168, 76, 0.25);
    border-radius: 16px;
    max-width: 580px;
    margin: 0 auto;
    padding: 44px 36px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
  }
  .logo {
    text-align: center;
    font-size: 26px;
    font-weight: 700;
    font-family: 'Georgia', serif;
    color: #C9A84C;
    letter-spacing: 1.5px;
    margin-bottom: 30px;
    text-transform: uppercase;
  }
  .greeting {
    font-size: 18px;
    font-weight: 600;
    color: #F0EDE6;
    margin-bottom: 12px;
  }
  .subtitle {
    font-size: 14px;
    color: #9C9080;
    line-height: 1.6;
    margin-bottom: 26px;
  }
  .section-title {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #5A5248;
    margin-bottom: 10px;
    font-weight: 600;
  }
  .inquiry-details {
    background-color: #242424;
    border-radius: 8px;
    padding: 16px 20px;
    margin-bottom: 26px;
    font-size: 13px;
    line-height: 1.6;
    color: #9C9080;
  }
  .inquiry-details strong {
    color: #F0EDE6;
  }
  .response-box {
    background: linear-gradient(135deg, rgba(201, 168, 76, 0.08) 0%, rgba(0,0,0,0) 100%);
    border-left: 4px solid #C9A84C;
    border-radius: 8px;
    padding: 20px 24px;
    margin-bottom: 32px;
    font-size: 14px;
    line-height: 1.7;
    color: #F0EDE6;
  }
  .btn-gold {
    background-color: #C9A84C;
    color: #0D0D0D !important;
    text-decoration: none;
    font-weight: 600;
    padding: 14px 28px;
    border-radius: 8px;
    font-size: 14px;
    display: inline-block;
    margin-bottom: 30px;
    text-align: center;
  }
  .btn-gold:hover {
    background-color: #E8C97A;
  }
  .footer-divider {
    border-top: 1px solid rgba(201, 168, 76, 0.1);
    margin: 30px 0 20px 0;
  }
  .footer {
    text-align: center;
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
    
    <div class="greeting">Hello {{ $inquiry->name }},</div>
    <div class="subtitle">
      Thank you for reaching out to us. An administrator has reviewed your inquiry regarding our custom acrylic creations and provided a response.
    </div>

    <div class="section-title">Your Original Inquiry</div>
    <div class="inquiry-details">
      <div><strong>Category:</strong> {{ $inquiry->category }}</div>
      @if($inquiry->product)
        <div><strong>Product:</strong> {{ $inquiry->product }}</div>
      @endif
      @if($inquiry->contact)
        <div><strong>Contact Mobile:</strong> {{ $inquiry->contact }}</div>
      @endif
      <div style="margin-top: 8px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 8px;">
        <em>"{{ $inquiry->message }}"</em>
      </div>
    </div>
    
    <div class="section-title">Official Administrator Reply</div>
    <div class="response-box">
      {!! nl2br(e($inquiry->response ?? 'Your inquiry has been successfully answered and resolved.')) !!}
    </div>
    
    <div style="text-align: center;">
      @php
        $wa = urlencode("Hi, I received the email reply regarding my inquiry about {$inquiry->category}. I'd like to discuss this further.");
      @endphp
      <a href="https://wa.me/918928202040?text={{ $wa }}" class="btn-gold" target="_blank">Connect directly on WhatsApp</a>
    </div>
    
    <div class="footer-divider"></div>
    
    <div class="footer">
      If you have further questions or need additional customization, feel free to reply directly to this email or chat with us on WhatsApp.<br><br>
      © {{ date('Y') }} <a href="{{ config('app.url') }}">Manas Creations</a>. All rights reserved.<br>
      Premium Acrylic Creations · Thane, Maharashtra, India
    </div>
  </div>
</div>
</body>
</html>
