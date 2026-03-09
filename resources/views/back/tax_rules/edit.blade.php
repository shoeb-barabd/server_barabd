@extends('layouts.app')
@section('title','Edit Tax Rule')
@section('page_title','Edit Tax Rule')
@section('breadcrumb','Tax Rules / Edit')

@section('content')
@include('back.partials.flash')

<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.tax-rules.update', $rule) }}" method="post">
      @method('PUT')
      @include('back.tax_rules._form', ['rule' => $rule, 'countries' => $countries])
    </form>
  </div>
</div>
@endsection
