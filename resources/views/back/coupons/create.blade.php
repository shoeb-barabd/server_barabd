@extends('layouts.app')
@section('title','New Coupon')
@section('page_title','New Coupon')
@section('breadcrumb','Coupons / New')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header">
    <h5 class="m-0">Create Coupon</h5>
  </div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.coupons.store') }}">
      @include('back.coupons._form')
    </form>
  </div>
</div>
@endsection
