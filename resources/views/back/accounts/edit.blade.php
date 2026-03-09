@extends('layouts.app')
@section('title','Edit Account')
@section('page_title','Edit Account')
@section('breadcrumb','Accounts / Edit')

@section('content')
@include('back.partials.flash')
<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.accounts.update',$account) }}" method="post">
      @method('PUT')
      @include('back.accounts._form', ['account'=>$account,'countries'=>$countries])
    </form>
  </div>
</div>
@endsection
