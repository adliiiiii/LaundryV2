@extends('layouts.app') @section('title','Edit item') @section('heading','Item & harga')
@section('content')<div class="mb-4"><h1 class="h3 fw-bold">Edit item</h1></div><div class="card p-4" style="max-width:650px"><form method="POST" action="{{ route('items.update',$item) }}">@csrf @method('PUT') @include('items._form')</form></div>@endsection
