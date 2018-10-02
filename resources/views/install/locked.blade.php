@extends('install.layout')

@section('title', 'Installer Locked')

@section('content')
    <div class="alert alert-warning" role="alert">The installer is currently locked, please remove 'install.lock' file from the project directory to continue.</div>
    <a href="../install" class="btn btn-primary float-right"><i class="fas fa-sync"></i> Re-run Installer</a>
@endsection