@extends('layouts.app')
@section('title','Add Contact')
@section('page_title','Add Contact')
@section('breadcrumb','Contacts / Create')

@section('content')
@include('back.partials.flash')
<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.contacts.store') }}" method="post">
      @include('back.contacts._form', ['contact'=>$contact, 'accounts'=>$accounts])
    </form>
  </div>
</div>
@endsection
