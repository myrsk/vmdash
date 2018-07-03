<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Client;
use App\Server;

class VultrAPI extends Controller
{

    public function api_post($uri,$apipass,$params){
        $client = new Client(['base_uri' => 'https://api.vultr.com/v1/']); 
            try {
    
            $response = $client->post($uri, [
                'headers'  => ['API-Key' => $apipass, 'Accept' => 'application/json'],
                'form_params' => (isset($params)) ? $params : null
                ]);
            $result = $response->getBody()->getContents();
            $result = json_decode($result, true);
    
        }
        catch (BadResponseException $e) {
    
            $result['error'] = $e->getResponse()->getBody()->getContents();
        }
            return $result;
    }
    
    public function api_get($uri,$apipass){
        $client = new Client(['base_uri' => 'https://api.vultr.com/v1/']); 
            try {
    
            $response = $client->get($uri, [
                'headers'  => ['API-Key' => $apipass, 'Accept' => 'application/json'],
            ]);
            $result = $response->getBody()->getContents();
            $result = json_decode($result, true);
    
        }
            catch (BadResponseException $e) {
    
                $result['error'] = $e->getResponse()->getBody()->getContents();
            }
            return $result;
    }

    public function getServer($id)
    {
        $item = Server::findOrFail($id);
        $result = $this->api_get('server/list?SUBID='.$item->apiserverid,$item->apikey);
        $result_images = $this->api_get('server/os_change_list?SUBID='.$item->apiserverid,$item->apikey);
        $result_isos = $this->api_get('iso/list_public',$item->apikey);

        if (isset($result['error']))
        {
           $array['errormessage']=$result['error'];
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
        $array['api_model'] = null;
        $array['api_hostname'] = $result['label'];
        $array['api_os'] = $result['os'];
        $array['api_internalip'] = $result['internal_ip'];
        $array['api_ipv4'] = $result['main_ip'];
        $array['api_ipv6'] = $result['v6_main_ip'];
        $array['api_internalip'] = $result['internal_ip'];
        $array['api_datacenter'] = $result['location'] .' (' . $result['DCID'].')';
        $array['api_totalcores'] = $result['vcpu_count'];
        $array['api_directvncurl'] = $result['kvm_url'];
        $array['api_totalmemory'] = formatSize((preg_replace('/[^0-9.]+/', '', $result['ram'])*pow(1024,2)),$decimals=2); // from mb to bytes
        $array['api_totaldisk'] = formatSize((preg_replace('/[^0-9.]+/', '', $result['disk'])*pow(1024,3)),$decimals=2); // from gb to bytes
        $array['api_totalbw'] = formatSize(($result['allowed_bandwidth_gb']*pow(1024,3)),$decimals=2); //from gb to bytes
        $array['api_usedbw'] = formatSize(($result['current_bandwidth_gb']*pow(1024,3)),$decimals=2); //from gb to bytes
        $array['api_usedbwpercent'] = ($result['current_bandwidth_gb'] / 100) * $result['allowed_bandwidth_gb'];
        if ($result['power_status'] == 'running')
        {
            $array['api_status'] = 'online';
        }
        elseif ($result['power_status'] == 'stopped')
        {
            $array['api_status'] = 'offline';
        }
        else
        {
            $array['api_status'] = $result['power_status'];
        }

        $array['api_images'] = array_map(function($result_images) {
            return array(
                'id' => $result_images['OSID'],
                'name' => $result_images['name']
            );
        }, $result_images);

        $array['api_isos'] = array_map(function($result_isos) {
            return array(
                'id' => $result_isos['ISOID'],
                'name' => $result_isos['name']. '('.$result_isos['description'].')',
            );
        }, $result_isos);

        //available actions from provider
        $array['action_softreboot'] = false;
        $array['action_hardreboot'] = true;
        $array['action_shutdown'] = false;
        $array['action_poweroff'] = true;
        $array['action_poweron'] = true;
        $array['action_rootpasswordreset'] = false;
        $array['action_enablerescue'] = false;
        $array['action_disablerescue'] = false;
        $array['action_reinstallos'] = true;
        $array['action_directvnc'] = true;
        $array['action_attachiso'] = true;
        $array['action_removeiso'] = true;

        }

        

        return $array;
    }


    public function hardReboot($id)
    {
        
        $item = Server::findOrFail($id);
        $params['SUBID']=$item->apiserverid;
        $result = $this->api_post('server/reboot',$item->apikey,$params);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error'];
        }
        else {

          //
        }

        return $result;
    }


    public function poweron($id)
    {
        $item = Server::findOrFail($id);
        $params['SUBID']=$item->apiserverid;
        $result = $this->api_post('server/start',$item->apikey,$params);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error'];
        }
        else {

          //
        }

        return $result;
    }

    public function poweroff($id)
    {
        $item = Server::findOrFail($id);
        $params['SUBID']=$item->apiserverid;
        $result = $this->api_post('server/halt',$item->apikey,$params);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error'];
        }
        else {

          //
        }

        return $result;
    }


    public function reinstallos($id, $template)
    {

        $item = Server::findOrFail($id);
        $params['SUBID']=$item->apiserverid;
        $params['OSID']=$template;
        $result = $this->api_post('server/os_change',$item->apikey,$params);

        if (isset($result['error']))
        {
           $result['errormessage']=$result['error'];
        }
        else {
            //
        }

        return $result;
    }

    public function attachiso($id, $template)
    {
        $item = Server::findOrFail($id);
        $params['SUBID']=$item->apiserverid;
        $params['ISOID']=$template;
        $result = $this->api_post('server/iso_attach',$item->apikey,$params);

        if (isset($result->error))
        {
            $result['errormessage']=$result['error'];
        }
        else {

          //
        }

        return $result;
    }

    public function removeiso($id)
    {
        $item = Server::findOrFail($id);
        $params['SUBID']=$item->apiserverid;
        $result = $this->api_post('server/iso_detach',$item->apikey,$params);

        if (isset($result->error))
        {
            $result['errormessage']=$result['error'];
        }
        else {

          //
        }

        return $result;
    }
        
}

