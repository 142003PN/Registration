<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | @yield('section', 'Programmes') | {{ config('app.name', 'Registration Portal') }}</title>
    <style>
        :root {
            --primary: #0a8e2e;
            --primary-strong: #087b29;
            --ink: #17231a;
            --muted: #68746b;
            --line: #e1e9e3;
            --surface: #fff;
            --background: #f5f8f5;
            --danger: #b42318;
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
        .brand { color: #fff; font-size: 17px; font-weight: 750; text-decoration: none; }
        .topbar form { margin: 0; }
        .sign-out, .button {
            display: inline-flex;
            min-height: 40px;
            align-items: center;
            justify-content: center;
            padding: 9px 14px;
            border: 1px solid transparent;
            border-radius: 8px;
            color: #fff;
            background: var(--primary);
            cursor: pointer;
            font: inherit;
            font-size: 14px;
            font-weight: 650;
            text-decoration: none;
        }
        .sign-out { border-color: rgb(255 255 255 / 45%); background: transparent; }
        .sign-out:hover { background: var(--primary-strong); }
        .button:hover { background: var(--primary-strong); }
        .button-secondary { border-color: var(--line); color: var(--ink); background: #fff; }
        .button-secondary:hover { background: #f1f5f2; }
        .button-danger { background: var(--danger); }
        main { width: min(100% - 40px, 1120px); margin: 44px auto; }
        .eyebrow {
            margin: 0 0 8px;
            color: var(--primary-strong);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        h1 { margin: 0; font-size: clamp(28px, 5vw, 38px); letter-spacing: -.045em; }
        .intro { margin: 10px 0 28px; color: var(--muted); line-height: 1.6; }
        .panel {
            padding: 26px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--surface);
            box-shadow: 0 8px 25px rgb(21 66 34 / 4%);
        }
        .toolbar, .actions { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; }
        .toolbar { justify-content: space-between; margin-bottom: 18px; }
        .toolbar h2 { margin: 0; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 14px 12px; border-bottom: 1px solid var(--line); }
        th { color: var(--muted); font-size: 12px; letter-spacing: .06em; text-transform: uppercase; }
        td:last-child { width: 1%; white-space: nowrap; }
        .text-link { color: var(--primary-strong); font-weight: 650; text-decoration: none; }
        .text-link:hover { text-decoration: underline; }
        .inline-form { display: inline; }
        .inline-form button {
            padding: 0;
            border: 0;
            color: var(--danger);
            background: none;
            cursor: pointer;
            font: inherit;
            font-weight: 650;
        }
        .empty { padding: 24px 12px; color: var(--muted); text-align: center; }
        label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 650; }
        input[type="text"] {
            width: 100%;
            min-height: 46px;
            padding: 11px 13px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #fff;
            font: inherit;
        }
        select {
            width: 100%;
            min-height: 46px;
            padding: 11px 13px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #fff;
            font: inherit;
        }
        input:focus { border-color: var(--primary); outline: 3px solid rgb(10 142 46 / 14%); }
        .field { max-width: 560px; margin-bottom: 20px; }
        .hint { margin: 7px 0 0; color: var(--muted); font-size: 13px; }
        .error { margin: 7px 0 0; color: var(--danger); font-size: 13px; }
        .detail { margin: 0 0 22px; }
        .detail dt { margin-bottom: 6px; color: var(--muted); font-size: 13px; }
        .detail dd { margin: 0; font-size: 18px; font-weight: 650; }
        .pagination { margin-top: 20px; }
        @media (max-width: 600px) {
            .topbar { min-height: 64px; padding: 0 18px; }
            main { width: min(100% - 32px, 1120px); margin: 32px auto; }
            .panel { padding: 19px; }
            th, td { padding: 12px 8px; }
            td:last-child { white-space: normal; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('dashboard') }}">{{ config('app.name', 'Registration Portal') }} · Admin</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="sign-out" type="submit">Sign out</button>
        </form>
    </header>
    <main>
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success') || session('error'))
        <script>
            Swal.fire({
                icon: @json(session('success') ? 'success' : 'error'),
                title: @json(session('success') ? 'Success' : 'Error'),
                text: @json(session('success') ?? session('error')),
                confirmButtonColor: '#0a8e2e'
            });
        </script>
    @endif
</body>
</html>
