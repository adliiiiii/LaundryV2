<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') · Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{--navy:#13243a;--blue:#3478f6;--bg:#f4f7fb}body{background:var(--bg);color:#253247;font-family:system-ui,-apple-system,"Segoe UI",sans-serif}.sidebar{width:250px;position:fixed;inset:0 auto 0 0;background:var(--navy);color:#fff;padding:24px 16px;z-index:1030}.brand{font-weight:750;font-size:1.25rem;padding:0 12px 28px}.sidebar .nav-link{color:#b9c6d8;border-radius:10px;padding:11px 13px;margin:3px 0}.sidebar .nav-link:hover,.sidebar .nav-link.active{color:#fff;background:#253c5a}.main{margin-left:250px;min-height:100vh}.topbar{background:#fff;border-bottom:1px solid #e6ebf2;padding:15px 30px;display:flex;justify-content:space-between;align-items:center}.content{padding:28px 30px}.card{border:0;border-radius:14px;box-shadow:0 4px 18px #1b35500a}.stat{padding:21px}.stat small{color:#718096}.stat strong{display:block;font-size:1.7rem;margin-top:8px;color:#13243a}.table thead th{background:#f8fafc;color:#627087;font-weight:600;font-size:.83rem;border-bottom:0}.table td{vertical-align:middle}.badge-status{padding:.45em .7em;border-radius:20px;font-weight:600}.mobile-menu{display:none}
        @media(max-width:767px){.sidebar{position:static;width:auto;padding:12px 16px}.sidebar .brand{padding:0 4px 8px}.sidebar nav{display:flex;overflow:auto}.sidebar .nav-link{white-space:nowrap;margin:0 3px}.main{margin-left:0}.topbar{padding:12px 16px}.content{padding:20px 14px}.desktop-user{display:none}}
        @media print{.sidebar,.topbar,.no-print{display:none!important}.main{margin:0}.content{padding:0}.card{box-shadow:none}}
    </style>
    @stack('styles')
</head>
<body>
@auth
<aside class="sidebar">
    <div class="brand">🫧 Laundry Admin</div>
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('dashboard')?'active':'' }}" href="{{ route('dashboard') }}">▦ &nbsp; Dashboard</a>
        <a class="nav-link {{ request()->routeIs('categories.*')?'active':'' }}" href="{{ route('categories.index') }}">▤ &nbsp; Kategori</a>
        <a class="nav-link {{ request()->routeIs('items.*')?'active':'' }}" href="{{ route('items.index') }}">◇ &nbsp; Item / Harga</a>
        <a class="nav-link {{ request()->routeIs('orders.*')?'active':'' }}" href="{{ route('orders.index') }}">▣ &nbsp; Order</a>
        <a class="nav-link {{ request()->routeIs('estimasi.*')?'active':'' }}" href="{{ route('estimasi.index') }}">◷ &nbsp; Estimasi selesai</a>
        <a class="nav-link {{ request()->routeIs('aktivitas.*')?'active':'' }}" href="{{ route('aktivitas.index') }}">↻ &nbsp; Aktivitas</a>
        <a class="nav-link {{ request()->routeIs('reports.*')?'active':'' }}" href="{{ route('reports.index') }}">▥ &nbsp; Rekapan</a>
    </nav>
</aside>
<main class="main">
    <header class="topbar"><strong>@yield('heading', 'Ringkasan')</strong><div class="d-flex align-items-center gap-3"><span class="text-secondary desktop-user">{{ auth()->user()->name }}</span><form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-sm btn-outline-secondary">Keluar</button></form></div></header>
    <section class="content">
        @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif
        @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @yield('content')
    </section>
</main>
@else @yield('content') @endauth
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body></html>
