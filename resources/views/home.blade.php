@extends('layout.master');

@section('title')

GO Cabs - Dashboard

@endsection

@section('content')

@if(Session::get('user_role')==1)
	@include('dashboard.index')
@elseif(Session::get('user_role')==3)
	@include('dashboard.franchise-index')
@endif
@endsection
