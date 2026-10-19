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
        .table-heading { display: flex; align-items: center; gap: 10px; }
        .programme-count {
            display: inline-flex;
            min-width: 28px;
            height: 26px;
            align-items: center;
            justify-content: center;
            padding: 0 8px;
            border-radius: 999px;
            color: var(--primary-strong);
            background: #eaf6ed;
            font-size: 12px;
            font-weight: 750;
        }
        .import-form { margin-bottom: 24px; padding: 20px; border: 1px solid var(--line); border-radius: 10px; background: var(--background); }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 14px 12px; border-bottom: 1px solid var(--line); }
        th { color: var(--muted); font-size: 12px; letter-spacing: .06em; text-transform: uppercase; }
        td:last-child { width: 1%; white-space: nowrap; }
        .table-wrap { overflow-x: auto; border: 1px solid var(--line); border-radius: 10px; }
        .programmes-table { width: 100%; min-width: 520px; border-collapse: collapse; text-align: left; }
        .programmes-table th, .programmes-table td { padding: 15px 18px; border-bottom: 1px solid var(--line); }
        .programmes-table th {
            color: var(--muted);
            background: #f8faf8;
            font-size: 11px;
            font-weight: 750;
            letter-spacing: .09em;
            text-transform: uppercase;
        }
        .programmes-table tbody tr:last-child td { border-bottom: 0; }
        .programmes-table tbody tr { transition: background-color .15s ease; }
        .programmes-table tbody tr:hover { background: #f7fbf8; }
        .programme-name { font-weight: 650; }
        .programme-marker { display: inline-block; width: 8px; height: 8px; margin-right: 10px; border-radius: 50%; background: var(--primary); }
        .programmes-table .actions-heading, .programme-actions { width: 1%; white-space: nowrap; }
        .text-link, .inline-form button {
            display: inline-flex;
            min-height: 32px;
            align-items: center;
            justify-content: center;
            padding: 5px 9px;
            border: 0;
            border-radius: 6px;
            color: var(--primary-strong);
            background: transparent;
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            font-weight: 650;
            text-decoration: none;
        }
        .text-link:hover { background: #eaf6ed; }
        .text-link:focus-visible, .inline-form button:focus-visible { outline: 3px solid rgb(10 142 46 / 25%); outline-offset: 2px; }
        .inline-form { display: inline; }
        .inline-form button {
            color: var(--danger);
        }
        .inline-form button:hover { background: #fdf0ef; }
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
        input[type="file"] { width: 100%; color: var(--ink); font: inherit; }
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
            .programmes-table th, .programmes-table td { padding: 13px 12px; }
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
