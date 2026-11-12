@extends('layouts.app') @section('title','Tambah kategori') @section('heading','Kategori')
@section('content')<div class="mb-4"><h1 class="h3 fw-bold">Tambah kategori</h1></div><div class="card p-4" style="max-width:650px"><form method="POST" action="{{ route('categories.store') }}">@csrf @include('categories._form')</form></div>@endsection
