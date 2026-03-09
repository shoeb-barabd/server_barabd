@extends('layouts.app')
@section('title','Edit Currency')
@section('page_title','Edit Currency')
@section('breadcrumb','Currencies / Edit')

@section('content')
@include('back.partials.flash')
<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.currencies.update',$currency) }}" method="post">
      @method('PUT')
      @include('back.currencies._form', ['currency'=>$currency])
    </form>
  </div>
</div>
@endsection
