@extends('layouts.app')

@section('title','Edit Country')
@section('page_title','Edit Country')
@section('breadcrumb','Countries / Edit')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.countries.update', $country) }}" method="post">
      @method('PUT')
      @include('back.countries._form', ['country' => $country])
    </form>
  </div>
</div>
@endsection
