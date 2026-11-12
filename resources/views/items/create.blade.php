@extends('layouts.app') @section('title','Tambah item') @section('heading','Item & harga')
@section('content')<div class="mb-4"><h1 class="h3 fw-bold">Tambah item</h1></div><div class="card p-4" style="max-width:650px"><form method="POST" action="{{ route('items.store') }}">@csrf @include('items._form')</form></div>@endsection
