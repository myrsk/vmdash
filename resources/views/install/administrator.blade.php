@extends('install.layout')

@section('title', 'Create Administrator')

@section('content')
    <p>Enter your new administrator account details below. This will be the first user and will be used to login to vmDash.</p>

    @if ($errors->any())
    <div id="errors" class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <form action="{{ route('install.postAdministrator') }}" method="post">
        {{ csrf_field() }}
    
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="John Smith">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="jsmith@example.com" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Password Confirmation</label>
            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required>
        </div>
        
        <button type="submit" class="btn btn-primary float-right"><i class="fas fa-user-plus"></i> Create Account</button>
    </form>
@endsection