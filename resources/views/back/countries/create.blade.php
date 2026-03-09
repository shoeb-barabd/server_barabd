@extends('layouts.app')

@section('title','Add Country')
@section('page_title','Add Country')
@section('breadcrumb','Countries / Create')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.countries.store') }}" method="post">
      @include('back.countries._form', ['country' => $country])
    </form>
  </div>
</div>
@endsection
