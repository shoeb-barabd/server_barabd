@extends('layouts.app')
@section('title','Edit User')
@section('page_title','Edit User')
@section('breadcrumb','Users / Edit')

@section('content')
@include('back.partials.flash')
<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.users.update', $user) }}" method="post">
      @method('PUT')
      @include('back.users._form', ['user' => $user])
    </form>
  </div>
</div>
@endsection
