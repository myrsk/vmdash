@extends('admin.default')

@section('page-header')
  Server <small>{{ trans('app.add_new_item') }}</small>
@stop

@section('content')
  {!! Form::open([
      'action' => ['ServerController@store'],
      'files' => true
    ])
  !!}

  @include('admin.servers.form')

  <button type="submit" class="btn btn-dark">{{ trans('app.add_button') }}</button>
  
  {!! Form::close() !!}
@stop