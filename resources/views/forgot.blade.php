<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Poppins", Arial, sans-serif;
        }

        body {
            background: #f8f9fd;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 24px 12px;
            color: #1f2a3d;
        }

        .auth-wrap {
            background: #fff;
            border-radius: 18px;
            padding: 26px 28px 32px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.08);
            width: min(420px, 100%);
            text-align: center;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 18px;
            text-decoration: none;
            color: #1f2a3d;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .brand img {
            height: 42px;
            width: auto;
        }

        .illustration {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-bottom: 14px;
        }

        .illustration svg {
            width: 120px;
            height: auto;
            stroke: #202020;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 6px;
            color: #1f2a3d;
        }

        .subtext {
            font-size: 0.95rem;
            color: #5b6475;
            margin-bottom: 16px;
        }

        form {
            text-align: left;
            margin-top: 10px;
        }

        label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: #2a2f3a;
            margin-bottom: 6px;
        }

        .input-field {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d9dde6;
            border-radius: 999px;
            font-size: 0.95rem;
            color: #2a2f3a;
            background: #fbfbfd;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #ff6b3d;
            box-shadow: 0 0 0 3px rgba(255, 107, 61, 0.18);
            background: #fff;
        }

        .btn-submit {
            display: block;
            width: 100%;
            margin-top: 14px;
            padding: 12px 16px;
            border: none;
            border-radius: 999px;
            background: #D81E1E;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.12s ease, box-shadow 0.12s ease, background 0.12s ease;
        }

        .btn-submit:hover {
            background: #D81E1E;
            box-shadow: 0 10px 20px rgba(255, 107, 61, 0.25);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 14px;
            color: #2a2f3a;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .messages {
            margin-top: 12px;
            font-size: 0.9rem;
            text-align: center;
        }

        .message {
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 8px;
        }

        .message.success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .message.error {
            background: #fdecea;
            color: #D81E1E;
        }
    </style>
</head>
<body>

<div class="auth-wrap">
    <a href="{{ route('index') }}" class="brand">
        <img src="{{ asset('front/images/logo.png') }}" alt="baraBD logo">
        <span><span style="color:#D81E1E;">bara</span><span style="color:#016A4D;">bd Data Center</span>
    </a>

    <h1>Forgot your password?</h1>
    <p class="subtext">Enter your email so we can send you a reset link.</p>

    <form action="{{ route('forgot.password') }}" method="POST">
        @csrf
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="input-field" placeholder="e.g. name@gmail.com" value="{{ old('email') }}" required>
        @error('email')
            <div class="messages"><div class="message error">{{ $message }}</div></div>
        @enderror

        <button type="submit" class="btn-submit">Send Email</button>
    </form>

    @if (session('success'))
        <div class="messages"><div class="message success">{{ session('success') }}</div></div>
    @endif

    @if (session('error'))
        <div class="messages"><div class="message error">{{ session('error') }}</div></div>
    @endif

    <a href="{{ route('login') }}" class="back-link">&#8592; Back to Login</a>
</div>

</body>
</html>
