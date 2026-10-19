<!DOCTYPE html>
@extends('admin.programs.layout')

@section('title', 'Dashboard')
@section('section', 'Overview')

@section('content')
<style>
        :root { --primary:#087b29; --ink:#17231a; --muted:#68746b; --line:#e1e9e3; --surface:#fff; --background:#f5f8f5; }
        * { box-sizing:border-box; }
        body { min-height:100vh; margin:0; color:var(--ink); background:var(--background); font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; }
        .welcome { margin:10px 0 30px; color:var(--muted); line-height:1.6; }
        .stats { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:18px; margin-bottom:28px; }
        .stat-card,.panel { border:1px solid var(--line); border-radius:14px; background:var(--surface); box-shadow:0 8px 25px rgb(21 66 34 / 4%); }
        .stat-card { padding:22px; text-decoration:none; color:inherit; transition:transform .15s,box-shadow .15s; }
        .stat-card:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgb(21 66 34 / 9%); }
        .stat-label { color:var(--muted); font-size:14px; font-weight:650; }
        .stat-number { display:block; margin:12px 0 8px; color:var(--primary); font-size:36px; font-weight:800; letter-spacing:-.04em; }
        .stat-link { color:var(--primary); font-size:13px; font-weight:700; }
        .panel { padding:26px; }
        .panel h2 { margin:0 0 8px; font-size:18px; }
        .panel p { margin:0; color:var(--muted); line-height:1.6; }
        .account { display:flex; align-items:center; gap:14px; }
        .avatar { display:grid; width:46px; height:46px; flex:0 0 46px; place-items:center; border-radius:50%; color:var(--primary); background:#e7f5ea; font-size:18px; font-weight:750; }
        .account p { margin-top:3px; }
        @media(max-width:650px) { .stats { grid-template-columns:1fr; gap:12px; } .stat-card { padding:18px; } .stat-number { display:inline; margin:0 12px; font-size:28px; } }
        .stats { margin-top:28px; }
        @media(max-width:650px) { .stats { grid-template-columns:1fr; gap:12px; } .stat-card { padding:18px; } .stat-number { display:inline; margin:0 12px; font-size:28px; } }
        .dashboard-account { margin-top:24px; }
    </style>
        <p class="eyebrow">Administrator dashboard</p>
        <h1>Welcome, {{ auth()->user()->name }}</h1>
        <p class="welcome">Here is an overview of your registration system.</p>

        <section class="stats" aria-label="Registration totals">
            <a class="stat-card" href="{{ route('admin.students.index') }}"><span class="stat-label">Registered students</span><strong class="stat-number">{{ number_format($studentCount) }}</strong><span class="stat-link">Manage students →</span></a>
            <a class="stat-card" href="{{ route('admin.courses.index') }}"><span class="stat-label">Courses</span><strong class="stat-number">{{ number_format($courseCount) }}</strong><span class="stat-link">Manage courses →</span></a>
            <a class="stat-card" href="{{ route('admin.programs.index') }}"><span class="stat-label">Programmes</span><strong class="stat-number">{{ number_format($programCount) }}</strong><span class="stat-link">Manage programmes →</span></a>
        </section>

        <section class="panel dashboard-account" aria-labelledby="account-heading">
            <div class="account">
                <div class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
                <div><h2 id="account-heading">Administrator account</h2><p>{{ auth()->user()->email }}</p></div>
            </div>
        </section>
@endsection
