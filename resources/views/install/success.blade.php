@extends('install.layout')

@section('title', 'Installation Successful')

@section('content')
    <div class="alert alert-success" role="alert">vmDash has been successfully installed and configured. You can start adding servers by <a href="{{ url('/') }}" class="alert-link">logging in</a> with the your new credentials!</div>
@endsection