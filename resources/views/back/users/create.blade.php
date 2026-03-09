@extends('layouts.app')
@section('title','Add User')
@section('page_title','Add User')
@section('breadcrumb','Users / Create')

@section('content')
@include('back.partials.flash')
<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.users.store') }}" method="post">
      @include('back.users._form', ['user' => $user])
    </form>
  </div>
</div>
@endsection
