@extends('layouts.app')
@section('title','Edit Contact')
@section('page_title','Edit Contact')
@section('breadcrumb','Contacts / Edit')

@section('content')
@include('back.partials.flash')
<div class="card card-primary card-outline">
  <div class="card-body">
    <form action="{{ route('admin.contacts.update', $contact) }}" method="post">
      @method('PUT')
      @include('back.contacts._form', ['contact'=>$contact, 'accounts'=>$accounts])
    </form>
  </div>
</div>
@endsection
