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
            position: fixed;
            inset: 0 0 auto;
            z-index: 20;
            display: flex;
            min-height: 72px;
            align-items: center;
            justify-content: space-between;
            padding: 0 max(24px, calc((100vw - 1120px) / 2));
            color: #fff;
            background: var(--primary);
        }
        .brand { display: inline-flex; align-items: center; gap: 10px; color: #fff; font-size: 17px; font-weight: 750; text-decoration: none; }
        .brand-logo { display: block; width: 48px; height: 48px; object-fit: contain; }
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
        .admin-shell { display: flex; min-height: 100vh; padding-top:72px; }
        .sidebar { position: fixed; top: 72px; bottom: 0; left: 0; z-index: 10; width: 244px; overflow-y: auto; padding: 28px 16px; border-right: 1px solid var(--line); background: #fff; }
        .sidebar-label { margin: 0 12px 12px; color: #89948b; font-size: 11px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
        .sidebar-nav { display: grid; gap: 5px; }
        .sidebar-link { display: flex; min-height: 44px; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 8px; color: #4d5b50; font-size: 14px; font-weight: 650; text-decoration: none; }
        .sidebar-link:hover { color: var(--primary-strong); background: #f1f8f2; }
        .sidebar-link[aria-current="page"] { color: var(--primary-strong); background: #eaf6ed; }
        .sidebar-icon { width: 20px; color: #748178; text-align: center; }
        .content-area { min-width: 0; flex: 1; margin-left:244px; }
        main { width: min(100% - 48px, 1120px); margin: 40px auto; }
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
        td .actions { display:flex; flex-flow:row nowrap; align-items:center; gap:4px; white-space:nowrap; }
        td .actions .inline-form { display:inline-flex; margin:0; }
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
        .data-table { min-width: 600px; }
        .data-table thead th { position: sticky; top: 0; z-index: 1; padding: 14px 16px; background: #f8faf8; }
        .data-table tbody td { padding: 15px 16px; }
        .data-table tbody tr { transition: background-color .15s ease; }
        .data-table tbody tr:hover { background: #f7fbf8; }
        .data-table tbody tr:last-child td { border-bottom: 0; }
        .catalog-page-header { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px; margin-bottom:24px; }
        .catalog-heading h1 { font-size:clamp(26px,4vw,34px); }
        .catalog-page-actions { display:flex; align-items:center; gap:10px; }
        .catalog-card { padding:0; overflow:hidden; }
        .catalog-card-heading { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:20px 22px; border-bottom:1px solid var(--line); }
        .catalog-card-heading h2 { margin:0; font-size:16px; }
        .catalog-card-body { padding:18px 22px 12px; }
        .js-live-search-controls { display:flex; flex-wrap:wrap; align-items:center; gap:10px; margin-bottom:16px; }
        .js-live-search-controls > div { flex:1 1 210px; }
        .js-live-search-controls input, .js-live-search-controls select { min-height:42px; border:1px solid var(--line); border-radius:8px; padding:9px 12px; color:var(--ink); background:#fff; font:inherit; }
        .js-jquery-pagination { padding:14px 0 4px; }
        .js-jquery-pagination > span { color:var(--muted); font-size:13px; font-weight:550; }
        .js-jquery-pagination > div { gap:14px !important; }
        .js-jquery-pagination select { width:auto; min-height:38px; padding:7px 34px 7px 11px; border:1px solid var(--line); border-radius:8px; color:#465349; background:#fff; font:inherit; font-size:13px; }
        .js-jquery-pagination nav { display:block; }
        .js-jquery-pagination .pagination { display:flex; align-items:center; gap:5px; margin:0; padding:0; list-style:none; }
        .js-jquery-pagination .page-item { margin:0; }
        .js-jquery-pagination .page-link { display:grid; min-width:36px; height:36px; place-items:center; padding:0 10px; border:1px solid var(--line); border-radius:8px; color:#435047; background:#fff; cursor:pointer; font:inherit; font-size:13px; font-weight:650; transition:color .15s,background .15s,border-color .15s,box-shadow .15s; }
        .js-jquery-pagination .page-link:hover:not(:disabled) { border-color:#acd7b5; color:var(--primary-strong); background:#f1f8f2; }
        .js-jquery-pagination .page-item.active .page-link { border-color:var(--primary-strong); color:#fff; background:var(--primary-strong); box-shadow:0 2px 5px rgb(8 123 41 / 18%); }
        .js-jquery-pagination .page-link:disabled { color:#a6afa8; background:#f7f9f7; cursor:not-allowed; }
        .js-jquery-pagination .page-link:focus-visible, .js-jquery-pagination select:focus-visible { outline:3px solid rgb(10 142 46 / 20%); outline-offset:2px; }
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
            .admin-shell { display:block; }
            .admin-shell { display:block; padding-top:64px; }
            .sidebar { position:sticky; top:64px; bottom:auto; z-index:15; width:auto; max-height:none; overflow:visible; padding:10px 14px; border-right:0; border-bottom:1px solid var(--line); }
            .content-area { margin-left:0; }
            .sidebar-label { display:none; }
            .sidebar-nav { display:flex; gap:4px; overflow-x:auto; }
            .sidebar-link { flex:0 0 auto; min-height:40px; padding:8px 10px; }
            .sidebar-icon { display:none; }
            main { width: min(100% - 32px, 1120px); margin: 28px auto; }
            .panel { padding: 19px; }
            .programmes-table th, .programmes-table td { padding: 13px 12px; }
            .catalog-card { padding:0; }
            .catalog-card-heading,.catalog-card-body { padding-left:15px; padding-right:15px; }
            .js-jquery-pagination { align-items:flex-start !important; }
            .js-jquery-pagination > div { width:100%; justify-content:space-between; gap:10px !important; }
            .js-jquery-pagination .page-link { min-width:34px; height:34px; padding:0 8px; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('dashboard') }}">
            <img class="brand-logo" src="{{ asset('images/Logo.png') }}" alt="Mukuba University logo">
            <span>{{ config('app.name', 'Registration Portal') }} · Admin</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="sign-out" type="submit">Sign out</button>
        </form>
    </header>
    <div class="admin-shell">
        <aside class="sidebar" aria-label="Admin navigation">
            <p class="sidebar-label">Workspace</p>
            <nav class="sidebar-nav">
                <a class="sidebar-link" href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif><span class="sidebar-icon" aria-hidden="true">⌂</span>Dashboard</a>
                <a class="sidebar-link" href="{{ route('admin.students.index') }}" @if(request()->routeIs('admin.students.*')) aria-current="page" @endif><span class="sidebar-icon" aria-hidden="true">♙</span>Students</a>
                <a class="sidebar-link" href="{{ route('admin.courses.index') }}" @if(request()->routeIs('admin.courses.*')) aria-current="page" @endif><span class="sidebar-icon" aria-hidden="true">▤</span>Courses</a>
                <a class="sidebar-link" href="{{ route('admin.programs.index') }}" @if(request()->routeIs('admin.programs.*')) aria-current="page" @endif><span class="sidebar-icon" aria-hidden="true">▦</span>Programmes</a>
            </nav>
        </aside>
        <div class="content-area"><main>@yield('content')</main></div>
    </div>
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
