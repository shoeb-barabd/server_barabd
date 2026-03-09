@extends('layouts.app')
@section('title','Edit Rate')
@section('page_title','Edit Rate')
@section('breadcrumb','Exchange Rates / Edit')

@section('content')
@include('back.partials.flash')
<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.exchange-rates.update',$rate) }}" method="post">
      @method('PUT')
      @include('back.exchange_rates._form', ['rate'=>$rate,'currencies'=>$currencies])
    </form>
  </div>
</div>
@endsection
