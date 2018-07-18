@extends('install.layout')

@section('title', 'Requirements Checker')

@section('content')
    <p>This process checks if your server and PHP configuration meets the requirements for running this vmDash version. It checks version of PHP, if appropriate PHP extensions have been loaded, and if PHP directives are set correctly.</p>
    
    @if ($status)
        <div id="errors" class="alert alert-success" role="alert">Your server configuration fully match all requirements for this vmDash version.</div>
    @else
        <div id="errors" class="alert alert-danger" role="alert">Your server configuration does not fully match all requirements for this vmDash version.</div>
    @endif

    <h6 class="mb-3">Requirements</h6>
    
    <ul id="requirements">
        @foreach ($requirements as $requirement => $info)
            @if ($info['status'])
                <li><i class="text-success far fa-check-circle"></i>  
            @else
                <li><i class="text-danger far fa-times-circle"></i>  
            @endif

            {{ $info['message'] }}</li>
        @endforeach
    </ul>
    
    <form action="{{ route('install.postRequirements') }}" method="post">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-primary float-right"{{ !$status ? ' disabled' : '' }}>Continue <i class="fas fa-chevron-right"></i></button>
    </form>
@endsection