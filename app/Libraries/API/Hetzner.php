<?php

namespace App\Libraries\API;

use App\Server;

class Hetzner extends Provider {

    public static $uri = 'https://api.hetzner.cloud/v1/';
    public static $params = [
        'headers'  => ['content-type' => 'application/json', 'Accept' => 'application/json']
    ];
    
    public static function getServer($id) {
        $server = Server::findOrFail($id);

        self::$params['headers']['Authorization'] = "Bearer ".$server->apikey;

        $result = self::apiQuery('GET', self::$uri . 'servers/' . $server->apiserverid, self::$params);
        $result_images = self::apiQuery('GET', self::$uri . 'images', self::$params);
        $result_isos = self::apiQuery('GET', self::$uri . 'isos', self::$params);

        if (isset($result['error']))
           $array['errormessage'] = $result['error']['message'];
        else {
            //values from DB
            $array['db_id'] = $server->id;
            $array['db_name'] = $server->name;
            $array['db_location'] = $server->location;
            $array['db_provider'] = $server->provider;
            $array['db_providerurl'] = $server->provider_url;
            $array['db_sshkey'] = $server->sshkey;
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

    public static function powerOn($id) {
        $server = Server::findOrFail($id);

        self::$params['headers']['Authorization'] = "Bearer ".$server->apikey;

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/actions/poweron',self::$params);

        if (isset($result['error']))
            $result['errormessage'] = $result['error']['message'];

        return $result;
    }

    public static function powerOff($id)
    {
        $server = Server::findOrFail($id);

        self::$params['headers']['Authorization'] = "Bearer ".$server->apikey;

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/actions/poweroff',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error']['message'];

        return $result;
    }

    public static function shutDown($id) {

        $server = Server::findOrFail($id);

        self::$params['headers']['Authorization'] = "Bearer ".$server->apikey;

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/actions/shutdown',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error']['message'];

        return $result;
    }

    public static function softReboot($id) {

        $server = Server::findOrFail($id);

        self::$params['headers']['Authorization'] = "Bearer ".$server->apikey;

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/actions/reboot',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error']['message'];

        return $result;
    }

    public static function hardReboot($id) {

        $server = Server::findOrFail($id);

        self::$params['headers']['Authorization'] = "Bearer ".$server->apikey;

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/actions/reset',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error']['message'];

        return $result;
    }

    public static function reinstallOs($id, $template) {

        $server = Server::findOrFail($id);

       self::$params['headers']['Authorization'] = "Bearer ".$server->apikey;
       self::$params['json']['image'] = $template;

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/actions/rebuild',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error'];

        return $result;
    }

    public static function attachIso($id, $template) {
        $server = Server::findOrFail($id);

        self::$params['headers']['Authorization'] = "Bearer ".$server->apikey;
        self::$params['json']['iso'] = $template;

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/actions/attach_iso',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error'];

        return $result;
    }

    public static function removeIso($id) {

        $server = Server::findOrFail($id);

        self::$params['headers']['Authorization'] = "Bearer ".$server->apikey;

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/actions/detach_iso',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error']['message'];

        return $result;
    }

    public static function enableRescue($id) {

        $server = Server::findOrFail($id);

        self::$params['headers']['Authorization'] = "Bearer ".$server->apikey;

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/actions/enable_rescue',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error']['message'];

        return $result;
    }

    public static function disableRescue($id) {

        $server = Server::findOrFail($id);

        self::$params['headers']['Authorization'] = "Bearer ".$server->apikey;

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/actions/disable_rescue',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error']['message'];

        return $result;
    }

    public static function resetRootPassword($id) {

        $server = Server::findOrFail($id);

        self::$params['headers']['Authorization'] = "Bearer ".$server->apikey;

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/actions/reset_password',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error']['message'];

        return $result;
    }

    public static function handleErrors($e) {
        
        $result = $e->getResponse()->getBody()->getContents();
        $result = json_decode($result, true);
        return $result;
    }

}
