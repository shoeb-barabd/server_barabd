@extends('layouts.app')
@section('title','Add Currency')
@section('page_title','Add Currency')
@section('breadcrumb','Currencies / Create')

@section('content')
@include('back.partials.flash')
<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.currencies.store') }}" method="post">
      @include('back.currencies._form', ['currency'=>$currency])
    </form>
  </div>
</div>
@endsection

