@extends('layouts.app')
@section('title','Add Tax Rule')
@section('page_title','Add Tax Rule')
@section('breadcrumb','Tax Rules / Create')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.tax-rules.store') }}" method="post">
      @include('back.tax_rules._form', ['rule' => $rule, 'countries' => $countries])
    </form>
  </div>
</div>
@endsection
