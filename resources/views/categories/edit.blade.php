@extends('layouts.app') @section('title','Edit kategori') @section('heading','Kategori')
@section('content')<div class="mb-4"><h1 class="h3 fw-bold">Edit kategori</h1></div><div class="card p-4" style="max-width:650px"><form method="POST" action="{{ route('categories.update',$category) }}">@csrf @method('PUT') @include('categories._form')</form></div>@endsection
