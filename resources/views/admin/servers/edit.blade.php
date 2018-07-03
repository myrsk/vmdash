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
  
  @section('footer_scripts')
  <script>
    function setSelectValue (id, val) {
      document.getElementById(id).value = val;
  }
  setSelectValue('location', '{{$item->location}}');
  setSelectValue('type', '{{$item->type}}');
  </script>
@stop
@endsection
