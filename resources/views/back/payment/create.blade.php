@extends('layouts.app')
@section('title','New Payment')
@section('page_title','New Payment')
@section('breadcrumb','Payments / New')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header">
    <h5 class="m-0">Create Payment</h5>
  </div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.payments.store') }}">
      @include('back.payment._form', ['submitLabel' => 'Save Payment', 'isEdit' => false])
    </form>
  </div>
</div>
@endsection
