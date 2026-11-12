@extends('layouts.app')
@section('title','Masuk Admin')
@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center p-3" style="background:linear-gradient(140deg,#eaf1ff,#f7f9fc)">
    <div class="card p-4 p-md-5" style="width:min(440px,100%)"><div class="text-center mb-4"><div class="fs-1">🫧</div><h1 class="h3 fw-bold mt-2">Laundry Admin</h1><p class="text-secondary">Masuk untuk mengelola operasional laundry</p></div>
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
        <form method="POST" action="{{ route('login.store') }}">@csrf
            <div class="mb-3"><label class="form-label">Email</label><input class="form-control form-control-lg" type="email" name="email" value="{{ old('email') }}" required autofocus></div>
            <div class="mb-3"><label class="form-label">Password</label><input class="form-control form-control-lg" type="password" name="password" required></div>
            <div class="form-check mb-4"><input class="form-check-input" type="checkbox" name="remember" value="1" id="remember"><label class="form-check-label" for="remember">Ingat saya</label></div>
            <button class="btn btn-primary btn-lg w-100">Masuk</button>
        </form>
    </div>
</div>
@endsection
