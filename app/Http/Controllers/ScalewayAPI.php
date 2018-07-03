<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Client;
use App\Server;

class ScalewayAPI extends Controller
{

    public function api_post($uri,$apipass,$params){
        $client = new Client(['base_uri' => 'https://cp-par1.scaleway.com/']); 
            try {
    
            $response = $client->post($uri, [
                'headers'  => ['X-Auth-Token' => $apipass,'content-type' => 'application/json', 'Accept' => 'application/json'],
                'json' => (isset($params)) ? $params : null
            ]);
            $result = $response->getBody()->getContents();
            $result = json_decode($result, true);
    
        }
            catch (BadResponseException $e) {
    
                $e = $e->getResponse()->getBody()->getContents();
                $e = json_decode($e, true);
                $result['error'] = $e;
            }
            return $result;
    }
    
    public function api_get($uri,$apipass){
        $client = new Client(['base_uri' => 'https://cp-par1.scaleway.com/']); 
            try {
    
            $response = $client->get($uri, [
                'headers'  => ['X-Auth-Token' => $apipass,'content-type' => 'application/json', 'Accept' => 'application/json']
            ]);
            $result = $response->getBody()->getContents();
            $result = json_decode($result, true);
    
        }
            catch (BadResponseException $e) {
    
                $e = $e->getResponse()->getBody()->getContents();
                $e = json_decode($e, true);
                $result['error'] = $e;
            }
            return $result;
    }

    public function getServer($id)
    {
        $item = Server::findOrFail($id);
        $result = $this->api_get('servers/'.$item->apiserverid,$item->apipass);
        
        if (isset($result['error']))
        {
           $array['errormessage']=$result['error']['message'].' ('.$result['error']['type'].')';
        }
        else {

        //values from DB
        $array['db_id'] = $item->id;
        $array['db_name'] = $item->name;
        $array['db_location'] = $item->location;
        $array['db_provider'] = $item->provider;
        $array['db_providerurl'] = $item->provider_url;
        $array['db_sshkey'] = $item->sshkey;

        //values from remote API from provider
        $array['api_model'] = $result['server']['commercial_type'];
        $array['api_hostname'] = $result['server']['hostname'];
        $array['api_os'] = $result['server']['image']['name'] . ' ('. $result['server']['image']['arch'] . ')';
        $array['api_ipv4'] = $result['server']['public_ip']['address'];
        $array['api_ipv6'] = $result['server']['ipv6']['address'];
        $array['api_internalip'] = $result['server']['private_ip'];
        $array['api_datacenter'] = $result['server']['location']['zone_id'];
        $array['api_totalcores'] = null;
        $array['api_totalmemory'] = null;
        $array['api_totaldisk'] = null;
        $array['api_totalbw'] = '∞';
        $array['api_usedbw'] = null;
        $array['api_usedbwpercent'] = '100';
        if ($result['server']['state'] == 'running')
        {
            $array['api_status'] = 'online';
        }
        elseif ($result['server']['state'] == 'stopped in place')
        {
            $array['api_status'] = 'offline';
        }
        else
        {
            $array['api_status'] = $result['server']['state'];
        }
        $array['api_images'] = null;
        $array['api_isos'] = null;

        //available actions from provider
        $array['action_softreboot'] = true;
        $array['action_hardreboot'] = false;
        $array['action_shutdown'] = true;
        $array['action_poweroff'] = true;
        $array['action_poweron'] = true;
        $array['action_rootpasswordreset'] = false;
        $array['action_enablerescue'] = false;
        $array['action_disablerescue'] = false;
        $array['action_reinstallos'] = false;
        $array['action_directvnc'] = false;
        $array['action_attachiso'] = false;
        $array['action_removeiso'] = false;

        }

        

        return $array;
    }

    public function softReboot($id)
    {
        $item = Server::findOrFail($id);
        $params['action']='reboot';
        $result = $this->api_post('servers/'.$item->apiserverid.'/action',$item->apipass,$params);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error']['message'].' ('.$result['error']['type'].')';
        }
        else {

          //
        }

        return $result;
    }



    public function shutdown($id)
    {
        $item = Server::findOrFail($id);
        $params['action']='stop_in_place';
        $result = $this->api_post('servers/'.$item->apiserverid.'/action',$item->apipass,$params);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error']['message'].' ('.$result['error']['type'].')';
        }
        else {

          //
        }

        return $result;
    }

    public function poweron($id)
    {
        $item = Server::findOrFail($id);
        $params['action']='poweron';
        $result = $this->api_post('servers/'.$item->apiserverid.'/action',$item->apipass,$params);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error']['message'].' ('.$result['error']['type'].')';
        }
        else {

          //
        }

        return $result;
    }

    public function poweroff($id)
    {
        $item = Server::findOrFail($id);
        $params['action']='poweroff';
        $result = $this->api_post('servers/'.$item->apiserverid.'/action',$item->apipass,$params);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error']['message'].' ('.$result['error']['type'].')';
        }
        else {

          //
        }

        return $result;
    }
        
}

