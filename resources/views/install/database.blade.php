@extends('install.layout')

@section('title', 'Database Setup')

@section('content')
    <p>Enter your database connection details below. The installer will automatically setup the database tables for vmDash.</p>

    @if ($errors->any())
    <div id="errors" class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <h6 class="mb-3">Database Conenction Details</h6>
    
    <form action="{{ route('install.postDatabase') }}" method="post">
        {{ csrf_field() }}
    
        <div class="form-group">
            <label for="host">Host</label>
            <input type="text" class="form-control" name="host" id="host" aria-describedby="hostHelp" placeholder='localhost' value="{{ old('host') }}" required>
            <small id="hostHelp" class="form-text text-muted">This is <code>localhost</code> if the database is hosted on the same server. If this doesn't work, enter your MySQL server address.</small>
        </div>

        <div class="form-group">
            <label for="port">Port</label>
            <input type="text" class="form-control" name="port" id="port" aria-describedby="portHelp" placeholder='3306' value="{{ old('port') }}" required>
            <small id="portHelp" class="form-text text-muted">This is usually <code>3306</code> for MySQL. If this doesn't work, get the port from your MySQL server.</small>
        </div>

        <div class="form-group">
            <label for="database">Database</label>
            <input type="text" class="form-control" name="database" id="database" aria-describedby="databaseHelp" placeholder="vmdash" value="{{ old('database') }}" required>
            <small id="databaseHelp" class="form-text text-muted">Create a new database in your MySQL server and enter the database name above.</small>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" id="username" aria-describedby="usernameHelp" placeholder="root" value="{{ old('username') }}" required>
            <small id="usernameHelp" class="form-text text-muted">The MySQL username of the user that has full priveleges to the database.</small>
        </div>

        <div class="form-group">
            <label for="password">Password</label>

            <div class="input-group">
                <input type="password" class="form-control" name="password" id="password" aria-describedby="passwordHelp">
                <div class="input-group-append" onclick="maskPassword()">
                    <span class="input-group-text"><i id="passwordIcon" class="fas fa-eye"></i></span>
                </div>
            </div>

            <small id="passwordHelp" class="form-text text-muted">The password of the MySQL user entered above.</small>
        </div>
        
        <button type="submit" class="btn btn-primary float-right"><i class="fas fa-database"></i> Setup Database</button>
    </form>
@endsection