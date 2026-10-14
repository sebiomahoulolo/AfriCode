@extends('formateurs.layouts.app')
@section('page-title', 'Mes notifications')

@section('content')
@include('partials.notifications-list', ['role' => 'formateur'])
@endsection
