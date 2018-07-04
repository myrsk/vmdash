@extends('admin.default')

@section('page-header')
  Server <small>{{ trans('app.update_item') }}</small>
@stop

@section('content')
  {!! Form::model($item, [
      'action' => ['ServerController@update', $item->id],
      'method' => 'put', 
      'files' => true
    ])
  !!}

  @include('admin.servers.form')

  <button type="submit" class="btn btn-dark">{{ trans('app.edit_button') }}</button>
  
  {!! Form::close() !!}
@stop
