@extends('layouts.app')
@section('title','Edit Coupon')
@section('page_title','Edit Coupon')
@section('breadcrumb','Coupons / Edit')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header">
    <h5 class="m-0">Edit Coupon: {{ $coupon->code }}</h5>
  </div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.coupons.update', $coupon) }}">
      @method('PUT')
      @include('back.coupons._form')
    </form>
  </div>
</div>
@endsection
