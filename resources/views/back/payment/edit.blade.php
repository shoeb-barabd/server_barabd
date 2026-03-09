@extends('layouts.app')
@section('title','Edit Payment')
@section('page_title','Edit Payment')
@section('breadcrumb','Payments / Edit')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <h5 class="m-0">Edit Payment ({{ $payment->tran_id }})</h5>
    <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-outline-secondary btn-sm ms-auto">
      <i class="bi bi-eye"></i> View
    </a>
  </div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.payments.update', $payment) }}">
      @include('back.payment._form', ['submitLabel' => 'Update Payment', 'isEdit' => true])
    </form>
  </div>
</div>
@endsection
