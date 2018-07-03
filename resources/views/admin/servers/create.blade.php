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

  @section('footer_scripts')
<script>
  $(document).ready(function () {
  $("#type").change(function()
        {
            if($(this).val() == "ScalewayAPI")
        {
                      $("#helper_apikey").show();
                      $("#helper_apipass").show();
                      $("#helper_apiserverid").show();
                      $("#helper_friendlyname").show();
                      $("#helper_hostname").show();
                      $("#helper_ipv4").show();
                      $("#helper_sshport").show();
                      $("#helper_provider").show();
                      $("#helper_providerurl").show();
                      $("#helper_location").show();
                      $("#helper_sshkey").show();
        }
          else if($(this).val() == "HetznerAPI")
        {
                      $("#helper_apiurl").hide();
                      $("#helper_apipass").hide();
                      $("#helper_apikey").show();
                      $("#helper_apiserverid").show();
                      $("#helper_friendlyname").show();
                      $("#helper_hostname").show();
                      $("#helper_ipv4").show();
                      $("#helper_sshport").show();
                      $("#helper_provider").show();
                      $("#helper_providerurl").show();
                      $("#helper_location").show();
                      $("#helper_sshkey").show();
        }
        else if($(this).val() == "VultrAPI")
        {
                      $("#helper_apiurl").hide();
                      $("#helper_apikey").show();
                      $("#helper_apipass").hide();
                      $("#helper_apiserverid").show();
                      $("#helper_friendlyname").show();
                      $("#helper_hostname").show();
                      $("#helper_ipv4").show();
                      $("#helper_sshport").show();
                      $("#helper_provider").show();
                      $("#helper_providerurl").show();
                      $("#helper_location").show();
                      $("#helper_sshkey").show();
        }
        else if($(this).val() == "")
        {
                      $("#helper_apiurl").hide();
                      $("#helper_apikey").hide();
                      $("#helper_apipass").hide();
                      $("#helper_apiserverid").hide();
                      $("#helper_friendlyname").hide();
                      $("#helper_hostname").hide();
                      $("#helper_ipv4").hide();
                      $("#helper_sshport").hide();
                      $("#helper_provider").hide();
                      $("#helper_providerurl").hide();
                      $("#helper_location").hide();
                      $("#helper_sshkey").hide();
        }
            });
                      $("#helper_apiurl").hide();
                      $("#helper_apikey").hide();
                      $("#helper_apipass").hide();
                      $("#helper_apiserverid").hide();
                      $("#helper_friendlyname").hide();
                      $("#helper_hostname").hide();
                      $("#helper_ipv4").hide();
                      $("#helper_sshport").hide();
                      $("#helper_provider").hide();
                      $("#helper_providerurl").hide();
                      $("#helper_location").hide();
                      $("#helper_sshkey").hide();
                    });
</script>
@stop
@endsection