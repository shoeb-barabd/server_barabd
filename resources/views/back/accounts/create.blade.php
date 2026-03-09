@extends('layouts.app')
@section('title','Add Account')
@section('page_title','Add Account')
@section('breadcrumb','Accounts / Create')

@section('content')
@include('back.partials.flash')
<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.accounts.store') }}" method="post">
      @include('back.accounts._form', ['account'=>$account,'countries'=>$countries])
    </form>
  </div>
</div>
@endsection
