@extends('admin.default')

@section('page-header')
  Server <small>details</small>
@stop

@section('content')

<span class="flag-icon flag-icon-gr"></span>
<span class="flag-icon flag-icon-gr flag-icon-squared"></span>

<div class="row gap-20 masonry pos-r">
        <div class="masonry-sizer col-md-6"></div>
        <div class="masonry-item  w-100">
            <div class="row gap-20">
<h3 class="mB-5"><small>
@if ($result['api_status'] == 'online')
<i class="fa fa-circle c-green-500"></i>
@elseif ($result['api_status'] == 'offline')
<i class="fa fa-circle c-red-500"></i>
@else
<i class="fa fa-circle c-yellow-500"></i>
@endif
</small> {{$result['db_name']}}</h3>
<br>
            </div>
            <div class="pull-right">
            <button type="button"class="btn btn-outline-dark" onClick="window.location.reload()"><i class="fa fa-refresh"></i> Refresh</button> @if($result['db_sshkey'])<button type="button" data-toggle="modal" data-target="#sshcommandsModal" class="btn btn-outline-dark"><i class="fa fa-terminal"></i> SSH Commands</button>@endif
            <div class="dropdown" style="display: inline;">
  <button class="btn btn-outline-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  <i class="fa fa-cog"></i> Actions
  </button>
  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
    @if($result['action_softreboot'])<a class="dropdown-item" href="#" server-id="{{$result['db_id']}}" server-action="softreboot">Soft Reboot</a>@endif
    @if($result['action_hardreboot'])<a class="dropdown-item" href="#" server-id="{{$result['db_id']}}" server-action="hardreboot">Hard Reboot</a>@endif
    @if($result['action_shutdown'])<a class="dropdown-item" href="#" server-id="{{$result['db_id']}}" server-action="shutdown">Shut Down</a>@endif
    @if($result['action_poweroff'])<a class="dropdown-item" href="#" server-id="{{$result['db_id']}}" server-action="poweroff">Power Off</a>@endif
    @if($result['action_poweron'])<a class="dropdown-item" href="#" server-id="{{$result['db_id']}}" server-action="poweron">Power On</a>@endif
    @if($result['action_rootpasswordreset'])<a class="dropdown-item" href="#" server-id="{{$result['db_id']}}" server-action="rootpasswordreset">Root Password Reset</a>@endif
    @if($result['action_enablerescue'])<a class="dropdown-item" href="#" server-id="{{$result['db_id']}}" server-action="enablerescue">Enable Rescue Mode</a>@endif
    @if($result['action_disablerescue'])<a class="dropdown-item" href="#" server-id="{{$result['db_id']}}" server-action="disablerescue">Disable Rescue Mode</a>@endif
    @if($result['action_reinstallos'])<a class="dropdown-item" href="#" data-toggle="modal" data-target="#reinstallModal">Reinstall OS</a>@endif
    @if($result['action_attachiso'])<a class="dropdown-item" href="#" data-toggle="modal" data-target="#isoModal">Attach ISO</a>@endif
    @if($result['action_removeiso'])<a class="dropdown-item" href="#" server-id="{{$result['db_id']}}" server-action="removeiso">Remove ISO</a>@endif
    @if($result['action_directvnc'])<a class="dropdown-item" href="{{$result['api_directvncurl']}}" target="_blank">Launch VNC Session</a>@endif
    <a class="dropdown-item" href="{{ route(ADMIN . '.servers.edit', $result['db_id']) }}">Edit Server</a>
  </div>
</div>
        </div>
        </div>
        <div class="masonry-item col-12">
            <!-- #Site Visits ==================== -->
            <div class="bd bgc-white">
                <div class="peers fxw-nw@lg+ ai-s">
                    <div class="peer peer-greed w-70p@lg+ w-100@lg- p-20">
                        <div class="">
                            <div class="layers">
                                <!-- Widget Title -->
                                <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">Overview</h6>
                                </div>
                                <!-- Today Weather Extended -->
                                <div class="layer w-100 mY-15">
                                    <div class="layers bdB">
                                    <div class="layer w-100 bdT pY-5">
                                            <div class="peers ai-c jc-sb fxw-nw">
                                                <div class="peer">
                                                    <span>Location</span>
                                                </div>
                                                <div class="peer ta-r">
                                                    <span class="fw-600 c-grey-800"><img src="{{asset('images/flags/'.$result['db_location'].'.png')}}"></span>
                                                </div>
                                            </div>
                                        </div>
                                        @if($result['api_model'])
                                    <div class="layer w-100 bdT pY-5">
                                            <div class="peers ai-c jc-sb fxw-nw">
                                                <div class="peer">
                                                    <span>Model</span>
                                                </div>
                                                <div class="peer ta-r">
                                                    <span class="fw-600 c-grey-800">{{$result['api_model']}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($result['api_status'])
                                        <div class="layer w-100 bdT pY-5">
                                            <div class="peers ai-c jc-sb fxw-nw">
                                                <div class="peer">
                                                    <span>Status</span>
                                                </div>
                                                <div class="peer ta-r">
                                                    <span class="fw-600 c-grey-800">{{$result['api_status']}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($result['api_hostname'])
                                        <div class="layer w-100 bdT pY-5">
                                            <div class="peers ai-c jc-sb fxw-nw">
                                                <div class="peer">
                                                    <span>Hostname</span>
                                                </div>
                                                <div class="peer ta-r">
                                                    <span class="fw-600 c-grey-800">{{$result['api_hostname']}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($result['api_os'])
                                        <div class="layer w-100 bdT pY-5">
                                            <div class="peers ai-c jc-sb fxw-nw">
                                                <div class="peer">
                                                    <span>Operating System</span>
                                                </div>
                                                <div class="peer ta-r">
                                                    <span class="fw-600 c-grey-800">{{$result['api_os']}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($result['api_ipv4'])
                                        <div class="layer w-100 bdT pY-5">
                                            <div class="peers ai-c jc-sb fxw-nw">
                                                <div class="peer">
                                                    <span>IPv4</span>
                                                </div>
                                                <div class="peer ta-r">
                                                    <span class="fw-600 c-grey-800 clipboardjscopy" data-clipboard-text="{{$result['api_ipv4']}}" data-toggle="tooltip" data-placement="top" title="Click To Copy">{{$result['api_ipv4']}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($result['api_ipv6'])
                                        <div class="layer w-100 bdT pY-5">
                                            <div class="peers ai-c jc-sb fxw-nw">
                                                <div class="peer">
                                                    <span>IPv6</span>
                                                </div>
                                                <div class="peer ta-r">
                                                <span class="fw-600 c-grey-800 clipboardjscopy" data-clipboard-text="{{$result['api_ipv6']}}" data-toggle="tooltip" data-placement="top" title="Click To Copy">{{$result['api_ipv6']}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($result['api_internalip'])
                                        <div class="layer w-100 bdT pY-5">
                                            <div class="peers ai-c jc-sb fxw-nw">
                                                <div class="peer">
                                                    <span>Internal IP</span>
                                                </div>
                                                <div class="peer ta-r">
                                                <span class="fw-600 c-grey-800 clipboardjscopy" data-clipboard-text="{{$result['api_internalip']}}" data-toggle="tooltip" data-placement="top" title="Click To Copy">{{$result['api_internalip']}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($result['api_datacenter'])
                                        <div class="layer w-100 bdT pY-5">
                                            <div class="peers ai-c jc-sb fxw-nw">
                                                <div class="peer">
                                                    <span>Datacenter</span>
                                                </div>
                                                <div class="peer ta-r">
                                                    <span class="fw-600 c-grey-800">{{$result['api_datacenter']}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($result['db_provider'])
                                        <div class="layer w-100 bdT pY-5">
                                            <div class="peers ai-c jc-sb fxw-nw">
                                                <div class="peer">
                                                    <span>Provider</span>
                                                </div>
                                                <div class="peer ta-r">
                                                    <span class="fw-600 c-grey-800">@if($result['db_providerurl'])<a href="{{ $result['db_providerurl'] }}" target="_blank">{{$result['db_provider']}}</a>@else{{$result['db_provider']}}@endif</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="peer bdL p-20 w-30p@lg+ w-100p@lg-">
                        <div class="layers">
                            <div class="layer w-100">
                                <!-- Progress Bars -->
                                <div class="layers">
                                    @if($result['api_totalcores'])
                                    <div class="layer w-100">
                                        <h5 class="mB-5">CPU</h5>
                                        <small class="fw-600 c-grey-700">Allocated Core(s)</small>
                                        <span class="pull-right c-grey-600 fsz-sm">{{$result['api_totalcores']}}</span>
                                        <div class="progress mT-10">
                                            <div class="progress-bar bgc-grey-500" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%;"> <span class="sr-only">100%</span></div>
                                        </div>
                                    </div>
                                    @endif
                          
                                    @if($result['api_totalmemory'])
                                    <div class="layer w-100 mT-15">
                                        <h5 class="mB-5">RAM</h5>
                                        <small class="fw-600 c-grey-700">Allocated Memory</small>
                                        <span class="pull-right c-grey-600 fsz-sm">{{$result['api_totalmemory']}}</span>
                                        <div class="progress mT-10">
                                            <div class="progress-bar bgc-grey-500" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%;"> <span class="sr-only">100%</span></div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($result['api_totaldisk'])
                                    <div class="layer w-100 mT-15">
                                        <h5 class="mB-5">HDD</h5>
                                        <small class="fw-600 c-grey-700">Allocated Space</small>
                                        <span class="pull-right c-grey-600 fsz-sm">{{$result['api_totaldisk']}}</span>
                                        <div class="progress mT-10">
                                            <div class="progress-bar bgc-grey-500" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%;"> <span class="sr-only">100%</span></div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($result['api_totalbw'])
                                    <div class="layer w-100 mT-15">
                                        <h5 class="mB-5">Bandwidth</h5>
                                        <small class="fw-600 c-grey-700">@if($result['api_usedbw']) Amount Used @else Allocated Bandwidth @endif</small>
                                        <span class="pull-right c-grey-600 fsz-sm">@if($result['api_usedbw']) {{$result['api_usedbw']}} /@endif {{$result['api_totalbw']}}</span>
                                        <div class="progress mT-10">
                                            <div class="progress-bar bgc-grey-500" role="progressbar" aria-valuenow="{{$result['api_usedbwpercent']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$result['api_usedbwpercent']}}%;"> <span class="sr-only">{{$result['api_usedbwpercent']}}%</span></div>
                                        </div>
                                    </div>
                                    @endif


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>






  

  <div class="row mB-40">
  <div class="col-sm-8">

@if ($result['action_reinstallos'])
<div class="modal fade" id="reinstallModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reinstall OS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="myForm">
            <div class="form-group">
              <label for="name">Select Operating System:</label>
              <select class="form-control" name="ostemplate" id="ostemplate">
              @foreach ($result['api_images'] as $template)
                <option value="{{$template['id']}} ">{{$template['name']}}</option>
                @endforeach
                </select>
            </div>
            <a class="btn btn-danger btn-block" href="#" server-id="{{$result['db_id']}}" server-action="reinstallos">Confirm Reinstall</a>
          </form>
          <br>
          <div id="cardblock" class="card card-block bg-faded" style="display:none;"><div id="wait" style="display:none;width:69px;height:89px;"><img src='{{asset('images/spinner.gif')}}' width="64" height="64" /><br>Loading...</div>
        </div>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  </div>
</div>
@endif
@if ($result['action_attachiso'])
<div class="modal fade" id="isoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reinstall OS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="myForm">
            <div class="form-group">
              <label for="name">Select ISO:</label>
              <select class="form-control" name="isotemplate" id="isotemplate">
              @foreach ($result['api_isos'] as $template)
                <option value="{{$template['id']}} ">{{$template['name']}}</option>
                @endforeach
                </select>
            </div>
            <a class="btn btn-dark btn-block" href="#" server-id="{{$result['db_id']}}" server-action="attachiso">Attach ISO</a>
          </form>
          <br>
          <div id="cardblock" class="card card-block bg-faded" style="display:none;"><div id="wait" style="display:none;width:69px;height:89px;"><img src='{{asset('images/spinner.gif')}}' width="64" height="64" /><br>Loading...</div>
        </div>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  </div>
</div>
@endif
<!-- Modal -->
<div class="modal fade" id="sshcommandsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">SSH Commands</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="myForm">
            <div class="form-group">
              <label for="name">Type:</label>
              <select class="form-control" name="type" id="type">
                <option value="host">Host</option>
                <option value="nslookup">Nslookup</option>  
                <option value="ping">Ping</option>
                <option value="ping6">Ping v6</option>
                <option value="tracert">Trace Route</option>
                <option value="tracert6">Trace Route v6</option>
                <option value="dd">DD Test</option>
                <option value="bench">Bench.sh Benchmark</option>
                </select>
            </div>
            <div id="helper_inputfield" class="form-group">
               <label for="price">IP or Host:</label>
               <input type="text" class="form-control" id="iphost">
             </div>
            <button class="btn btn-dark btn-block" id="ajaxSubmit">Submit</button>
          </form>

          <br>
          <div id="sshwait" style="display:none;width:69px;height:89px;"><img src='{{asset('images/spinner.gif')}}' width="64" height="64" /><br>Loading...</div>
          <div id="sshcardblock" class="card card-block bg-faded" style="display:none;">
<pre class="sshcommandvalue" id="sshcommandvalue"></pre>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  </div>
</div>
@section('footer_scripts')
<script>
         jQuery(document).ready(function(){
            NProgress.configure({ showSpinner: false });
            $(document).ajaxStart(function(){
                 $("#sshcommandvalue").html('');
                 $("#cardblock").css("display", "block");
                 $("#sshcardblock").css("display", "none");
                 $("#wait").css("display", "block");
                 $("#sshwait").css("display", "block");
            });
            $(document).ajaxComplete(function(){
                $("#sshcardblock").css("display", "block");
                $("#wait").css("display", "none");
                $("#sshwait").css("display", "none");
            });

            jQuery('a[server-id]').click(function(e){
                if (confirm('Are you sure you want to perform the selected option to this machine?')) {
                    NProgress.start();
                e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
            var serverid = $(this).attr('server-id');
            var serveraction = $(this).attr('server-action');
            jQuery.ajax({
                        url: '{{ url('/admin/servers/') }}'+'/'+serveraction,
                        type: 'POST',
                        data: {id: serverid,
                            image: jQuery('#ostemplate').val(),
                            iso: jQuery('#isotemplate').val(),},
                        success: function(data){
                            NProgress.done();
                            swal ( data['title'] ,  data['message'] ,  data['style'] )
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            NProgress.done();
                            swal ( "Error" ,  "Action was not successful!" ,  "error" )
                        }
                    });
            return false;
                }
            });

            jQuery('#ajaxSubmit').click(function(e){
               e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
               jQuery.ajax({
                  url: "{{ url('/admin/ajax/sshcommands') }}",
                  method: 'post',
                  data: {
                     id: "{{$result['db_id']}}",
                     type: jQuery('#type').val(),
                     iphost: jQuery('#iphost').val()
                  },
                  success: function(result){
                    {
                    $("#sshcommandvalue").html(result);
                    //alert((result)); 
                    }
                  },
                error: function(data) {
                    var errors = data.responseJSON;
                    $("#sshcommandvalue").html(errors['message']);
                }});
               });
            });


            $("#type").change(function()
        {
        if($(this).val() == "dd")
        {
                      $("#helper_inputfield").hide();
                      $("#sshcardblock").css("display", "none");
                      $("#sshcommandvalue").html('');
        }
        else if($(this).val() == "bench")
        {
                      $("#helper_inputfield").hide();
                      $("#sshcardblock").css("display", "none");
                      $("#sshcommandvalue").html('');
        }
        else
        {
                      $("#helper_inputfield").show();
                      $("#sshcardblock").css("display", "none");
                      $("#sshcommandvalue").html('');
        }
            });
                      
                    
      </script>
@stop
@endsection