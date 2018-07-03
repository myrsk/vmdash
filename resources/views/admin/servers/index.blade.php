@extends('admin.default')

@section('page-header')
    Servers <small>{{ trans('app.manage') }}</small>
@endsection

@section('content')


<div class="mB-20">
    <a href="{{ route(ADMIN . '.servers.create') }}" class="btn btn-outline-dark pull-right">
    <i class="fa fa-plus"></i> Add Server
    </a>
    <br>
</div>

<div class="row">
    <div class="col-md-12">
      <div class="bgc-white bd bdrs-3 p-20 mB-20">

        <table id="dataTable" class="table table-hover" cellspacing="0" width="100%">
            
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Friendly Name</th>
                        <th>Hostname</th>
                        <th>IPv4</th>
                        <th>Provider</th>
                        <th>Actions</th>
                    </tr>
                </thead>
             
                <tfoot>
                    <tr>
                        <th>Location</th>
                        <th>Friendly Name</th>
                        <th>Hostname</th>
                        <th>IPv4</th>
                        <th>Provider</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
             
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td><img src="{{asset('images/flags/'.$item->location.'.png')}}"></td>
                            <td><a href="{{ route(ADMIN . '.servers.show', $item->id) }}">{{ $item->name }}</a></td>
                            <td><a class="clipboardjscopy" data-clipboard-text="{{ $item->hostname }}" data-toggle="tooltip" data-placement="top" title="Click To Copy">{{ $item->hostname }}</a></td>
                            <td><a class="clipboardjscopy" data-clipboard-text="{{ $item->ipv4 }}" data-toggle="tooltip" data-placement="top" title="Click To Copy">{{ $item->ipv4 }}</a></td>
                            <td>@if($item->provider_url)<a href="{{ $item->provider_url }}" target="_blank">{{ $item->provider }}</a>@else{{ $item->provider }}@endif</td>
                            <td>
                                <ul class="list-inline">
                                     <li class="list-inline-item">
                                        <a href="{{ route(ADMIN . '.servers.show', $item->id) }}" title="{{ trans('app.edit_title') }}" class="btn btn-dark btn-sm"><span class="ti-eye"></span></a></li> 
                                    <li class="list-inline-item">
                                        <a href="{{ route(ADMIN . '.servers.edit', $item->id) }}" title="{{ trans('app.edit_title') }}" class="btn btn-dark btn-sm"><span class="ti-pencil"></span></a></li>
                                    <li class="list-inline-item">
                                        {!! Form::open([
                                            'class'=>'delete',
                                            'url'  => route(ADMIN . '.servers.destroy', $item->id), 
                                            'method' => 'DELETE',
                                            ]) 
                                        !!}

                                            <button class="btn btn-danger btn-sm" title="{{ trans('app.delete_title') }}"><i class="ti-trash"></i></button>
                                            
                                        {!! Form::close() !!}
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            
        </table>
      </div>
    </div>
  </div>    
@endsection