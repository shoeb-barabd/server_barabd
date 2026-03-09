@extends('layouts.app')
@section('title','Add Rate')
@section('page_title','Add Rate')
@section('breadcrumb','Exchange Rates / Create')

@section('content')
@include('back.partials.flash')
<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.exchange-rates.store') }}" method="post">
      @include('back.exchange_rates._form', ['rate'=>$rate,'currencies'=>$currencies])
    </form>
  </div>
</div>
@endsection
