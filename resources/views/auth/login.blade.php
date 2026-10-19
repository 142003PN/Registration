<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin login | {{ config('app.name', 'Registration Portal') }}</title>
    <style>
        :root {
            --primary: #0a8e2e;
            --primary-strong: #087b29;
            --ink: #17231a;
            --muted: #68746b;
            --line: #dce5de;
            --surface: #fff;
            --background: #f4f8f5;
        }
        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 24px;
            color: var(--ink);
            background: radial-gradient(circle at 15% 15%, #e2f3e6, transparent 34%), var(--background);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .login-card {
            width: min(100%, 430px);
            padding: 40px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--surface);
            box-shadow: 0 20px 55px rgb(21 66 34 / 9%);
        }
        .brand-mark {
            display: grid;
            width: 48px;
            height: 48px;
            place-items: center;
            border-radius: 14px;
            color: #fff;
            background: var(--primary);
            font-size: 22px;
            font-weight: 800;
        }
        h1 { margin: 24px 0 8px; font-size: 28px; letter-spacing: -.04em; }
        .intro { margin: 0 0 28px; color: var(--muted); line-height: 1.55; }
        label { display: block; margin: 18px 0 8px; font-size: 14px; font-weight: 650; }
        input[type="email"], input[type="password"] {
            width: 100%;
            min-height: 48px;
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: 9px;
            color: var(--ink);
            background: #fff;
            font: inherit;
        }
        input:focus { border-color: var(--primary); outline: 3px solid rgb(10 142 46 / 14%); }
        .remember {
            display: flex;
            align-items: center;
            gap: 9px;
            margin: 18px 0 24px;
            color: var(--muted);
            font-size: 14px;
        }
        .remember label { margin: 0; font-weight: 450; }
        .remember input { accent-color: var(--primary); }
        button {
            width: 100%;
            min-height: 49px;
            border: 0;
            border-radius: 9px;
            color: #fff;
            background: var(--primary);
            cursor: pointer;
            font: inherit;
            font-weight: 700;
        }
        button:hover { background: var(--primary-strong); }
        .error {
            margin: 8px 0 0;
            color: #b42318;
            font-size: 13px;
        }
        @media (max-width: 480px) { .login-card { padding: 30px 24px; } }
    </style>
</head>
<body>
    <main class="login-card">
        <div class="brand-mark" aria-hidden="true">A</div>
        <h1>Admin sign in</h1>
        <p class="intro">Sign in with your administrator account to access the dashboard.</p>

        <form method="POST" action="{{ route('login.authenticate') }}">
            @csrf
            <label for="email">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
            @error('email')
                <p class="error" role="alert">{{ $message }}</p>
            @enderror

            <label for="password">Password</label>
            <input id="password" type="password" name="password" autocomplete="current-password" required>
            @error('password')
                <p class="error" role="alert">{{ $message }}</p>
            @enderror

            <div class="remember">
                <input id="remember" type="checkbox" name="remember" value="1">
                <label for="remember">Keep me signed in</label>
            </div>

            <button type="submit">Sign in</button>
        </form>
    </main>
</body>
</html>
