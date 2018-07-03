<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use App\Server;

class HetznerAPI extends Controller
{

    public function api_post($uri,$apipass,$params){
        $client = new Client(['base_uri' => 'https://api.hetzner.cloud/v1/']); 
            try {
    
            $response = $client->post($uri, [
                'headers'  => ['Authorization' => 'Bearer '.$apipass.'','content-type' => 'application/json', 'Accept' => 'application/json'],
                'json' => (isset($params)) ? $params : null
                ]);
            $result = $response->getBody()->getContents();
            $result = json_decode($result, true);
    
        }
            catch (RequestException $e) {
    
                $result = $e->getResponse()->getBody()->getContents();
                $result = json_decode($result, true);
                
            }
            return $result;
    }
    
    public function api_get($uri,$apipass){
        $client = new Client(['base_uri' => 'https://api.hetzner.cloud/v1/']); 
            try {
    
            $response = $client->get($uri, [
                'headers'  => ['Authorization' => 'Bearer '.$apipass.'','content-type' => 'application/json', 'Accept' => 'application/json'],
            ]);
            $result = $response->getBody()->getContents();
            $result = json_decode($result, true);
    
        }
            catch (RequestException $e) {
    
                $result = $e->getResponse()->getBody()->getContents();
                $result = json_decode($result, true);
                
            }
            return $result;
    }

    public function getServer($id)
    {
        $item = Server::findOrFail($id);
        $result = $this->api_get('servers/'.$item->apiserverid,$item->apikey);
        $result_images = $this->api_get('images',$item->apikey);
        $result_isos = $this->api_get('isos',$item->apikey);

        if (isset($result['error']))
        {
           $array['errormessage']=$result['error']['message'];
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
        $array['api_model'] = $result['server']['server_type']['name'];
        $array['api_hostname'] = $result['server']['name'];
        $array['api_os'] = $result['server']['image']['description'];
        $array['api_ipv4'] = $result['server']['public_net']['ipv4']['ip'];
        $array['api_ipv6'] = $result['server']['public_net']['ipv6']['ip'];
        $array['api_internalip'] = null;
        $array['api_datacenter'] = $result['server']['datacenter']['description'] . ' (' . $result['server']['datacenter']['name'] . ')';
        $array['api_totalcores'] = $result['server']['server_type']['cores'];
        $array['api_directvncurl'] = null;
        $array['api_totalmemory'] = formatSize(($result['server']['server_type']['memory']*pow(1024,3)),$decimals=2);
        $array['api_totaldisk'] = formatSize(($result['server']['server_type']['disk']*pow(1024,3)),$decimals=2);
        $array['api_totalbw'] = formatSize($result['server']['included_traffic'],$decimals=2);
        $array['api_usedbw'] = formatSize($result['server']['outgoing_traffic'],$decimals=2);
        $array['api_usedbwpercent'] = ($result['server']['outgoing_traffic'] / 100) * $result['server']['included_traffic'];
        if ($result['server']['status'] == 'running')
        {
            $array['api_status'] = 'online';
        }
        elseif ($result['server']['status'] == 'off')
        {
            $array['api_status'] = 'offline';
        }
        else
        {
            $array['api_status'] = $result['server']['status'];
        }
        $result_images = $result_images['images'];
        $array['api_images'] = array_map(function($result_images) {
            return array(
                'id' => $result_images['id'],
                'name' => $result_images['description']
            );
        }, $result_images);
        $result_isos = $result_isos['isos'];
        $array['api_isos'] = array_map(function($result_isos) {
            return array(
                'id' => $result_isos['name'],
                'name' => $result_isos['description']
            );
        }, $result_isos);

        //available actions from provider
        $array['action_softreboot'] = true;
        $array['action_hardreboot'] = true;
        $array['action_shutdown'] = true;
        $array['action_poweroff'] = true;
        $array['action_poweron'] = true;
        $array['action_rootpasswordreset'] = true;
        $array['action_enablerescue'] = true;
        $array['action_disablerescue'] = true;
        $array['action_reinstallos'] = true;
        $array['action_directvnc'] = false;
        $array['action_attachiso'] = true;
        $array['action_removeiso'] = true;

        }

        

        return $array;
    }

    public function softReboot($id)
    {
        $item = Server::findOrFail($id);
        $result = $this->api_post('servers/'.$item->apiserverid.'/actions/reboot',$item->apikey,$params=null);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error']['message'];
        }
        else {

          //
        }

        return $result;
    }

    public function hardReboot($id)
    {
        $item = Server::findOrFail($id);
        $result = $this->api_post('servers/'.$item->apiserverid.'/actions/reset',$item->apikey,$params=null);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error']['message'];
        }
        else {

          //
        }


        return $result;
    }

    public function shutdown($id)
    {
        $item = Server::findOrFail($id);
        $result = $this->api_post('servers/'.$item->apiserverid.'/actions/shutdown',$item->apikey,$params=null);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error']['message'];
        }
        else {

          //
        }

        return $result;
    }

    public function poweron($id)
    {
        $item = Server::findOrFail($id);
        $result = $this->api_post('servers/'.$item->apiserverid.'/actions/poweron',$item->apikey,$params=null);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error']['message'];
        }
        else {

          //
        }
        

        return $result;
    }

    public function poweroff($id)
    {
        $item = Server::findOrFail($id);
        $result = $this->api_post('servers/'.$item->apiserverid.'/actions/poweroff',$item->apikey,$params=null);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error']['message'];
        }
        else {

          //
        }

        return $result;
    }

    public function enablerescue($id)
    {
        $item = Server::findOrFail($id);
        $result = $this->api_post('servers/'.$item->apiserverid.'/actions/enable_rescue',$item->apikey,$params=null);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error']['message'];
        }
        else {

          //
        }

        return $result;
    }

    public function disablerescue($id)
    {
        $item = Server::findOrFail($id);
        $result = $this->api_post('servers/'.$item->apiserverid.'/actions/disable_rescue',$item->apikey,$params=null);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error']['message'];
        }
        else {

          //
        }

        return $result;
    }

    public function rootpasswordreset($id)
    {
        $item = Server::findOrFail($id);
        $result = $this->api_post('servers/'.$item->apiserverid.'/actions/reset_password',$item->apikey,$params=null);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error']['message'];
        }
        else {

          //
        }

        return $result;
    }

    public function reinstallos($id, $template)
    {
        $item = Server::findOrFail($id);
        $params['image']=$template;
        $result = $this->api_post('servers/'.$item->apiserverid.'/actions/rebuild',$item->apikey,$params);

        if (isset($result->error))
        {
            $result['errormessage']=$result['error']['message'];
        }
        else {

          //
        }

        return $result;
    }

    public function attachiso($id, $template)
    {
        $item = Server::findOrFail($id);
        $params['iso']=$template;
        $result = $this->api_post('servers/'.$item->apiserverid.'/actions/attach_iso',$item->apikey,$params);

        if (isset($result->error))
        {
            $result['errormessage']=$result['error']['message'];
        }
        else {

          //
        }

        return $result;
    }

    public function removeiso($id)
    {
        $item = Server::findOrFail($id);
        $result = $this->api_post('servers/'.$item->apiserverid.'/actions/detach_iso',$item->apikey,$params=null);

        if (isset($result->error))
        {
            $result['errormessage']=$result['error']['message'];
        }
        else {

          //
        }

        return $result;
    }

    
        
}

