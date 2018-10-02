<?php

namespace App\Http\Controllers;
use App\Http\Controllers\HetznerAPI;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Server;
use Crypt_RSA;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use Response;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $items = Server::latest('updated_at')->get();
        
        return view('admin.servers.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.servers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, Server::rules());
        
        Server::create($request->all());

        return back()->withSuccess(trans('app.success_store'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $server = Server::findOrFail($id);
        $result = $this->getProviderLibrary($server->type)::getServer($id);
        
        if(isset($result['errormessage']))
        {
            return redirect()->back()->with('error', $result['errormessage']);
        }
 
        return view('admin.servers.show', compact('result'));
    }

    public function softReboot(Request $request)
    {
        
        $server = Server::findOrFail($request->id);
        $result = $this->getProviderLibrary($server->type)::softReboot($request->id);

        return Response::json(array(
            //originally (isset($result['errormessage']))
            'success' => (isset($result['error'])) ? 'false' : 'true',
            'data'   => $result,
            'title' => (isset($result['error'])) ? 'Oops' : 'Success',
            'message' => (isset($result['error'])) ? $result['errormessage'] : 'Your server has been rebooted',
            'style' => (isset($result['error'])) ? 'error' : 'success'
        )); 
    }

    public function hardReboot(Request $request)
    {
        
        $server = Server::findOrFail($request->id);
        $result = $this->getProviderLibrary($server->type)::hardReboot($request->id);

            return Response::json(array(
                'success' => (isset($result['error'])) ? 'false' : 'true',
                'data'   => $result,
                'title' => (isset($result['error'])) ? 'Oops' : 'Success',
                'message' => (isset($result['error'])) ? $result['errormessage'] : 'Your server has been  hard rebooted',
                'style' => (isset($result['error'])) ? 'error' : 'success'
            ));
        
         
    }

    public function shutdown(Request $request)
    {
        
        $server = Server::findOrFail($request->id);
        $result = $this->getProviderLibrary($server->type)::shutDown($request->id);
        
        return Response::json(array(
            'success' => (isset($result['error'])) ? 'false' : 'true',
            'data'   => $result,
            'title' => (isset($result['error'])) ? 'Oops' : 'Success',
            'message' => (isset($result['error'])) ? $result['errormessage'] : 'Your server has been shut down',
            'style' => (isset($result['error'])) ? 'error' : 'success'
        ));
    }

    public function poweron(Request $request)
    {
        
        $server = Server::findOrFail($request->id);
        $result = $this->getProviderLibrary($server->type)::powerOn($request->id);

        return Response::json(array(
            'success' => (isset($result['error'])) ? 'false' : 'true',
            'data'   => $result,
            'title' => (isset($result['error'])) ? 'Oops' : 'Success',
            'message' => (isset($result['error'])) ? $result['errormessage'] : 'Your server has been powered on',
            'style' => (isset($result['error'])) ? 'error' : 'success'
        ));
    }

    public function poweroff(Request $request)
    {
        
        $server = Server::findOrFail($request->id);
        $result = $this->getProviderLibrary($server->type)::powerOff($request->id);

        return Response::json(array(
            'success' => (isset($result['error'])) ? 'false' : 'true',
            'data'   => $result,
            'title' => (isset($result['error'])) ? 'Oops' : 'Success',
            'message' => (isset($result['error'])) ? $result['errormessage']: 'Your server has been powered off',
            'style' => (isset($result['error'])) ? 'error' : 'success'
        ));
    }

    public function enablerescue(Request $request)
    {
        
        $server = Server::findOrFail($request->id);
        $result = $this->getProviderLibrary($server->type)::enableRescue($request->id);

        return Response::json(array(
            'success' => (isset($result['error'])) ? 'false' : 'true',
            'data'   => $result,
            'title' => (isset($result['error'])) ? 'Oops' : 'Success',
            'message' => (isset($result['error'])) ? $result['errormessage'] : 'Please reboot your server to access rescue mode. Your rescue root password is: '.$result['root_password'],
            'style' => (isset($result['error'])) ? 'error' : 'success'
        ));

    }

    public function disablerescue(Request $request)
    {
        
        $server = Server::findOrFail($request->id);
        $result = $this->getProviderLibrary($server->type)::disableRescue($request->id);

        return Response::json(array(
            'success' => (isset($result['error'])) ? 'false' : 'true',
            'data'   => $result,
            'title' => (isset($result['error'])) ? 'Oops' : 'Success',
            'message' => (isset($result['error'])) ? $result['errormessage'] : 'Rescue mode has been disabled, please reboot your server to access it normally',
            'style' => (isset($result['error'])) ? 'error' : 'success'
        ));


    }

    public function rootpasswordreset(Request $request)
    {
        
        $server = Server::findOrFail($request->id);
        $result = $this->getProviderLibrary($server->type)::resetRootPassword($request->id);

        return Response::json(array(
            'success' => (isset($result['error'])) ? 'false' : 'true',
            'data'   => $result,
            'title' => (isset($result['error'])) ? 'Oops' : 'Success',
            'message' => (isset($result['error'])) ? $result['errormessage'] : 'Your root password has been successfuly reset. Your new password is: '.$result['root_password'],
            'style' => (isset($result['error'])) ? 'error' : 'success'
        ));


    }

    public function reinstallos(Request $request)
    {
        
        $server = Server::findOrFail($request->id);
        $result = $this->getProviderLibrary($server->type)::reinstallOs($request->id, $request->image);


        return Response::json(array(
            'success' => (isset($result['error'])) ? 'false' : 'true',
            'data'   => $result,
            'title' => (isset($result['error'])) ? 'Oops' : 'Success',
            'message' => (isset($result['error'])) ? $result['errormessage'] : 'Your server is being reinstalled',
            'style' => (isset($result['error'])) ? 'error' : 'success'
        ));
    }

    public function attachiso(Request $request)
    {
        
        $server = Server::findOrFail($request->id);
        $result = $this->getProviderLibrary($server->type)::attachIso($request->id, $request->iso);


        return Response::json(array(
            'success' => (isset($result['error'])) ? 'false' : 'true',
            'data'   => $result,
            'title' => (isset($result['error'])) ? 'Oops' : 'Success',
            'message' => (isset($result['error'])) ? $result['errormessage'] : 'ISO has been attached, please reboot and start a VNC/Console Session',
            'style' => (isset($result['error'])) ? 'error' : 'success'
        ));
    }

    public function removeiso(Request $request)
    {
        
        $server = Server::findOrFail($request->id);
        $result = $this->getProviderLibrary($server->type)::removeIso($request->id);


        return Response::json(array(
            'success' => (isset($result['error'])) ? 'false' : 'true',
            'data'   => $result,
            'title' => (isset($result['error'])) ? 'Oops' : 'Success',
            'message' => (isset($result['error'])) ? $result['errormessage'] : 'ISO has been removed, please reboot',
            'style' => (isset($result['error'])) ? 'error' : 'success'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Server::findOrFail($id);

        return view('admin.servers.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, Server::rules(true, $id));

        $item = Server::findOrFail($id);

        $item->update($request->all());

        return redirect()->route(ADMIN . '.servers.index')->withSuccess(trans('app.success_update'));
    }

    public function ajaxsshcommands(Request $request)
    {
        if($request->type !== 'dd' && $request->type !== 'bench')
        {
        $request->validate([
            'iphost' => ['required', 'regex:/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$|^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$|^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/i'],
        ]);
        }

        $item = Server::findOrFail($request->id);
        
        $ssh = new SSH2($item['ipv4']);
        $key = new RSA();
        $key->loadKey(($item['sshkey']));
        if (!$ssh->login('root', $key)) {
            exit('Failed to login to SSH. Please make sure your private SSH Key is correct.');
        }
        $ssh->setTimeout(0);
        
        
        if ($request->type == "ping")
        {
            $result=$ssh->exec("ping -c 4 $request->iphost");
        }
        elseif ($request->type == "ping6")
        {
            $result=$ssh->exec("ping6 -c 4 $request->iphost");
        }
        elseif ($request->type == "tracert")
        {
            $result=$ssh->exec("traceroute -4 $request->iphost");
        }
        elseif ($request->type == "tracert6")
        {
            $result=$ssh->exec("traceroute -6 $request->iphost");
        }
        elseif ($request->type == "host")
        {
            $result=$ssh->exec("host $request->iphost");
        }
        elseif ($request->type == "nslookup")
        {
            $result=$ssh->exec("nslookup $request->iphost");
        }
        elseif ($request->type == "dd")
        {
            $result=$ssh->exec("dd if=/dev/zero of=test bs=64k count=16k conv=fdatasync; rm test");
        }
        elseif ($request->type == "bench")
        {
            $result=$ssh->exec("wget -qO- https://gist.githubusercontent.com/myrsk/a80aa559f0f5d15af34711c4cb4cfa09/raw/1a55e73b7cc0d0b5772018a456b22bb4e0d1571e/bench.sh | bash");
        }
        else
        {
            $result="Not available type";
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Server::destroy($id);

        return back()->withSuccess(trans('app.success_destroy')); 
    }

    public function getProviderLibrary($provider) {

        return '\\App\\Libraries\\API\\' . $provider;
        
    }
}

