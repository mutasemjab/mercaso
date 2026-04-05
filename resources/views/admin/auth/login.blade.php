<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>California Cash &amp; Carry — Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('assets/admin/dist/css/adminlte.min.css') }}">
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 45%, #0f3460 100%);
      position: relative;
      overflow: hidden;
    }

    body::before {
      content: '';
      position: absolute;
      width: 650px; height: 650px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255,193,7,0.09) 0%, transparent 70%);
      top: -180px; right: -180px;
      pointer-events: none;
    }

    body::after {
      content: '';
      position: absolute;
      width: 500px; height: 500px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255,193,7,0.06) 0%, transparent 70%);
      bottom: -160px; left: -160px;
      pointer-events: none;
    }

    /* ── Floating badges ── */
    .deco-badge {
      position: fixed;
      background: rgba(255,193,7,0.08);
      border: 1px solid rgba(255,193,7,0.18);
      border-radius: 10px;
      padding: 7px 14px;
      font-size: 11px;
      color: rgba(255,193,7,0.65);
      font-weight: 600;
      letter-spacing: 0.4px;
      white-space: nowrap;
    }
    .deco-badge.tl { top: 28px; left: 28px; }
    .deco-badge.br { bottom: 28px; right: 28px; }

    /* ── Wrapper ── */
    .login-wrapper {
      width: 100%;
      max-width: 460px;
      padding: 24px 20px;
      position: relative;
      z-index: 10;
    }

    /* ── Brand ── */
    .brand-header { text-align: center; margin-bottom: 30px; }

    .brand-icon {
      width: 72px; height: 72px;
      background: linear-gradient(135deg, #ffc107, #e65c00);
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 14px;
      box-shadow: 0 8px 30px rgba(255,193,7,0.38);
    }
    .brand-icon i { font-size: 32px; color: #fff; }

    .brand-name {
      font-size: 25px;
      font-weight: 800;
      color: #fff;
      letter-spacing: -0.4px;
      line-height: 1.2;
    }
    .brand-name span { color: #ffc107; }

    .brand-sub {
      font-size: 11.5px;
      color: rgba(255,255,255,0.4);
      margin-top: 5px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* ── Card ── */
    .login-card {
      background: rgba(255,255,255,0.055);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 24px;
      padding: 38px 34px 34px;
      box-shadow: 0 24px 60px rgba(0,0,0,0.45);
    }

    .card-title {
      font-size: 17px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 4px;
    }
    .card-subtitle {
      font-size: 13px;
      color: rgba(255,255,255,0.4);
      margin-bottom: 26px;
    }

    /* ── Fields ── */
    .field-group { margin-bottom: 18px; }

    .field-label {
      display: block;
      font-size: 12.5px;
      font-weight: 500;
      color: rgba(255,255,255,0.65);
      margin-bottom: 7px;
    }

    .input-wrap { position: relative; direction: ltr; }

    .input-wrap .fi {
      position: absolute;
      left: 14px; top: 50%;
      transform: translateY(-50%);
      color: rgba(255,255,255,0.3);
      font-size: 14px;
      pointer-events: none;
      transition: color .2s;
    }

    .input-wrap input {
      width: 100%;
      height: 48px;
      padding: 0 16px 0 42px;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.11);
      border-radius: 12px;
      color: #fff;
      font-size: 14px;
      font-family: 'Inter', sans-serif;
      direction: ltr;
      text-align: left;
      outline: none;
      transition: border-color .2s, background .2s, box-shadow .2s;
    }
    .input-wrap input::placeholder { color: rgba(255,255,255,0.28); }
    .input-wrap input:focus {
      border-color: #ffc107;
      background: rgba(255,193,7,0.07);
      box-shadow: 0 0 0 3px rgba(255,193,7,0.15);
    }
    .input-wrap:focus-within .fi { color: #ffc107; }

    /* ── Validation error ── */
    .field-error {
      display: block;
      font-size: 12px;
      color: #ff6b6b;
      margin-top: 5px;
    }
    .field-error i { margin-right: 4px; }

    /* ── Divider ── */
    .divider {
      height: 1px;
      background: rgba(255,255,255,0.08);
      margin: 26px 0;
    }

    /* ── Submit ── */
    .btn-login {
      width: 100%;
      height: 50px;
      background: linear-gradient(135deg, #ffc107, #e65c00);
      border: none;
      border-radius: 12px;
      color: #1a1a2e;
      font-size: 15px;
      font-weight: 700;
      font-family: 'Inter', sans-serif;
      letter-spacing: 0.3px;
      cursor: pointer;
      box-shadow: 0 6px 22px rgba(255,193,7,0.35);
      transition: transform .15s, box-shadow .15s, filter .15s;
    }
    .btn-login:hover {
      transform: translateY(-1px);
      box-shadow: 0 10px 28px rgba(255,193,7,0.45);
      filter: brightness(1.06);
    }
    .btn-login:active {
      transform: translateY(0);
      box-shadow: 0 4px 14px rgba(255,193,7,0.3);
    }
    .btn-login i { margin-right: 7px; }

    /* ── Footer ── */
    .login-footer {
      text-align: center;
      margin-top: 22px;
      font-size: 11.5px;
      color: rgba(255,255,255,0.22);
    }

    @media (max-width: 500px) {
      .deco-badge { display: none; }
      .login-card { padding: 26px 18px; }
    }
  </style>
</head>

<body>

  <!-- Floating decorative badges -->
  <div class="deco-badge tl"><i class="fas fa-shopping-cart" style="margin-right:6px;"></i>Wholesale &amp; Retail</div>
  <div class="deco-badge br"><i class="fas fa-store" style="margin-right:6px;"></i>Supermarket Management</div>

  <div class="login-wrapper">

    <!-- Brand header -->
    <div class="brand-header">
      <div class="brand-icon">
        <i class="fas fa-shopping-basket"></i>
      </div>
      <div class="brand-name">California <span>Cash &amp; Carry</span></div>
      <div class="brand-sub">Supermarket Administration</div>
    </div>

    <!-- Login card -->
    <div class="login-card">
      <div class="card-title">Welcome back</div>
      <div class="card-subtitle">Sign in to your admin account to continue</div>

      <form action="{{ route('admin.login') }}" method="post" autocomplete="off">
        @csrf

        <!-- Username -->
        <div class="field-group">
          <label class="field-label" for="username">Username</label>
          <div class="input-wrap">
            <i class="fas fa-user fi"></i>
            <input
              type="text"
              id="username"
              name="username"
              placeholder="Enter your username"
              value="{{ old('username') }}"
              autofocus
            >
          </div>
          @error('username')
            <span class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>
          @enderror
        </div>

        <!-- Password -->
        <div class="field-group">
          <label class="field-label" for="password">Password</label>
          <div class="input-wrap">
            <i class="fas fa-lock fi"></i>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Enter your password"
            >
          </div>
          @error('password')
            <span class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</span>
          @enderror
        </div>

        <div class="divider"></div>

        <button type="submit" class="btn-login">
          <i class="fas fa-sign-in-alt"></i>Sign In
        </button>
      </form>
    </div>

    <div class="login-footer">
      &copy; {{ date('Y') }} California Cash &amp; Carry. All rights reserved.
    </div>

  </div>

  <!-- jQuery -->
  <script src="{{ asset('assets/admin/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
