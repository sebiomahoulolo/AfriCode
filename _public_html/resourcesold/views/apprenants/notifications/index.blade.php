@extends('apprenants.layouts.app')
@section('page-title', 'Mes notifications')

@section('content')
@include('partials.notifications-list', ['role' => 'apprenant'])
@endsection
