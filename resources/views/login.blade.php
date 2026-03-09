@php
    $showRegister = $showRegister ?? false;
@endphp
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Account Access | baraBD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-dC1XQ7Wv5ManIeZDV4SSQdlqzTeWY5Avzkdxl3pNGdisz8Iky3Uczdlz7YT1Nx1ESyqBbpt5TRSMXmN7mqg7GA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('front/css/login.css') }}" />
  </head>

  <body>
    <div class="brand-ribbon">
      <a href="{{ route('index') }}" class="brand-link">
        <span class="fw-bold"><span style="color:#D81E1E;">bara</span><span style="color:#016A4D;">bd Data Center</span></span>
      </a>
    </div>

    @if (session('success') || session('error') || $errors->any())
      <div class="auth-messages">
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
          <div class="alert alert-danger">
            <div class="alert-title">Please fix the following:</div>
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    @endif

    <div class="container{{ $showRegister ? ' active' : '' }}" id="container" data-default-mode="{{ $showRegister ? 'register' : 'login' }}">
      <div class="form-container sign-up">
        <form method="POST" action="{{ route('register.store') }}">
          @csrf
          <h1>Create Account</h1>

          <a class="google-login-btn" href="{{ route('google.redirect') }}">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" />
            <span>Continue with Google</span>
          </a>

          <p class="or">or use your email password</p>

          <div class="name-row">
            <div class="input-wrapper">
              <input type="text" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required />
              @if($showRegister)
                @error('first_name')
                  <span class="form-error">{{ $message }}</span>
                @enderror
              @endif
            </div>
            <div class="input-wrapper">
              <input type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required />
              @if($showRegister)
                @error('last_name')
                  <span class="form-error">{{ $message }}</span>
                @enderror
              @endif
            </div>
          </div>

          <div class="input-wrapper">
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
            @if($showRegister)
              @error('email')
                <span class="form-error">{{ $message }}</span>
              @enderror
            @endif
          </div>

          <div class="input-wrapper">
            <input type="password" name="password" placeholder="Password" required />
            @if($showRegister)
              @error('password')
                <span class="form-error">{{ $message }}</span>
              @enderror
            @endif
          </div>

          <div class="input-wrapper">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
          </div>

          <input type="hidden" name="role" value="admin" />

          <div class="form-group captcha-group">
            {!! NoCaptcha::display() !!}
            @if ($errors->has('g-recaptcha-response') && $showRegister)
              <span class="form-error">
                {{ $errors->first('g-recaptcha-response') }}
              </span>
            @endif
          </div>

          <button type="submit">Sign Up</button>
          <p class="switch-text">
            Already have an account?
            <a href="{{ route('login') }}" class="switch-link">Log In</a>
          </p>
        </form>
      </div>

      <div class="form-container sign-in">
        <form method="POST" action="{{ route('login.attempt') }}" novalidate>
          @csrf
          <h1>Log In</h1>

          <a class="google-login-btn" href="{{ route('google.redirect') }}">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" />
            <span>Continue with Google</span>
          </a>

          <p class="or">or use your email password</p>

          <div class="input-wrapper">
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
            @if(!$showRegister)
              @error('email')
                <span class="form-error">{{ $message }}</span>
              @enderror
            @endif
          </div>

          <div class="input-wrapper">
            <input type="password" name="password" placeholder="Password" required />
          </div>

          <div class="form-group captcha-group">
            {!! NoCaptcha::display() !!}
            @if ($errors->has('g-recaptcha-response') && !$showRegister)
              <span class="form-error">
                {{ $errors->first('g-recaptcha-response') }}
              </span>
            @endif
          </div>

          <div class="remember-forgot">
            <label class="remember-me">
              <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} />
              Remember Me
            </label>
            <a href="{{ route('forgot.page') }}">Forget Your Password?</a>
          </div>

          <button type="submit">Log In</button>
          <p class="switch-text">
            Don't have an account?
            <a href="{{ route('register') }}" class="switch-link">Sign Up</a>
          </p>
        </form>
      </div>

      <div class="toggle-container">
        <div class="toggle">
          <div class="toggle-panel toggle-left">
            <h1>Welcome Back!</h1>

            <p>Enter your personal details to use all of our site features.</p>

            <a href="{{ route('login') }}" class="register-link"><button class="hidden" id="login" type="button">Log In</button></a>
          </div>
          <div class="toggle-panel toggle-right">
            <h1>Hello,</h1>
            <p>Register with your personal details to use all of our site features.</p>
            <a href="{{ route('register') }}" class="register-link"><button class="hidden" id="register" type="button">Sign Up</button> </a>
          </div>
        </div>
      </div>
    </div>

    {!! NoCaptcha::renderJs() !!}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eg5kX9GB2DkGLumZ5qX6Ch12yvDqOiiMHDL/95B2S/bRMyCV2wAPOQgpdnXl16rDpD+s/COM24kTx5cRQprPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('front/js/login.js') }}"></script>
  </body>
</html>
