<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin dashboard | {{ config('app.name', 'Registration Portal') }}</title>
    <style>
        :root {
            --primary: #0a8e2e;
            --primary-strong: #087b29;
            --ink: #17231a;
            --muted: #68746b;
            --line: #e1e9e3;
            --surface: #fff;
            --background: #f5f8f5;
        }
        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            color: var(--ink);
            background: var(--background);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .topbar {
            display: flex;
            min-height: 72px;
            align-items: center;
            justify-content: space-between;
            padding: 0 max(24px, calc((100vw - 1120px) / 2));
            color: #fff;
            background: var(--primary);
        }
        .brand { font-size: 17px; font-weight: 750; letter-spacing: -.02em; }
        .topbar form { margin: 0; }
        .sign-out {
            padding: 9px 14px;
            border: 1px solid rgb(255 255 255 / 45%);
            border-radius: 8px;
            color: #fff;
            background: transparent;
            cursor: pointer;
            font: inherit;
            font-size: 14px;
            font-weight: 650;
        }
        .sign-out:hover { background: var(--primary-strong); }
        main { width: min(100% - 40px, 1120px); margin: 48px auto; }
        .eyebrow {
            margin: 0 0 8px;
            color: var(--primary-strong);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        h1 { margin: 0; font-size: clamp(28px, 5vw, 38px); letter-spacing: -.045em; }
        .welcome { margin: 10px 0 30px; color: var(--muted); line-height: 1.6; }
        .panel {
            max-width: 720px;
            padding: 28px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--surface);
            box-shadow: 0 8px 25px rgb(21 66 34 / 4%);
        }
        .panel h2 { margin: 0 0 8px; font-size: 18px; }
        .panel p { margin: 0; color: var(--muted); line-height: 1.6; }
        .panel-link {
            display: inline-block;
            margin-top: 18px;
            color: var(--primary-strong);
            font-weight: 700;
            text-decoration: none;
        }
        .panel-link:hover { text-decoration: underline; }
        .account { display: flex; align-items: center; gap: 14px; }
        .avatar {
            display: grid;
            width: 46px;
            height: 46px;
            flex: 0 0 46px;
            place-items: center;
            border-radius: 50%;
            color: var(--primary-strong);
            background: #e7f5ea;
            font-size: 18px;
            font-weight: 750;
        }
        .account p { margin-top: 3px; }
        @media (max-width: 520px) {
            .topbar { min-height: 64px; padding: 0 20px; }
            main { width: min(100% - 32px, 1120px); margin: 34px auto; }
            .panel { padding: 22px; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="brand">{{ config('app.name', 'Registration Portal') }} · Admin</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="sign-out" type="submit">Sign out</button>
        </form>
    </header>

    <main>
        <p class="eyebrow">Administrator dashboard</p>
        <h1>Welcome, {{ auth()->user()->name }}</h1>
        <p class="welcome">You are signed in and ready to manage the registration system.</p>

        <section class="panel" aria-labelledby="account-heading">
            <div class="account">
                <div class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <h2 id="account-heading">Administrator account</h2>
                    <p>{{ auth()->user()->email }}</p>
                </div>
            </div>
        </section>

        <section class="panel" aria-labelledby="programmes-heading" style="margin-top: 20px">
            <h2 id="programmes-heading">Programmes</h2>
            <p>Create, update, and remove the programmes available for registration.</p>
            <a class="panel-link" href="{{ route('admin.programs.index') }}">Manage programmes</a>
        </section>

        <section class="panel" aria-labelledby="courses-heading" style="margin-top: 20px">
            <h2 id="courses-heading">Courses</h2>
            <p>Create, update, and remove courses offered by each programme.</p>
            <a class="panel-link" href="{{ route('admin.courses.index') }}">Manage courses</a>
        </section>
    </main>
</body>
</html>
