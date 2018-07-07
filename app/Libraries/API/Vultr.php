<?php

namespace App\Libraries\API;

use App\Server;

class Vultr extends Provider {

    public static $uri = 'https://api.vultr.com/v1/';
    public static $params = [
        'headers'  => ['Accept' => 'application/json']
    ];
    
    public static function getServer($id) {
        $server = Server::findOrFail($id);

        self::$params['headers']['API-Key'] = $server->apikey;

        $result = self::apiQuery('GET', self::$uri . 'server/list?SUBID=' . $server->apiserverid, self::$params);
        $result_images = self::apiQuery('GET', self::$uri . 'server/os_change_list?SUBID=' . $server->apiserverid, self::$params);
        $result_isos = self::apiQuery('GET', self::$uri . 'iso/list_public', self::$params);

        if (isset($result['error']))
           $array['errormessage'] = $result['error'];
        else {
            $array['db_id'] = $server->id;
            $array['db_name'] = $server->name;
            $array['db_location'] = $server->location;
            $array['db_provider'] = $server->provider;
            $array['db_providerurl'] = $server->provider_url;
            $array['db_sshkey'] = $server->sshkey;

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
                $array['api_status'] = 'online';
            elseif ($result['power_status'] == 'stopped')
                $array['api_status'] = 'offline';
            else
                $array['api_status'] = $result['power_status'];

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

    public static function powerOn($id) {
        $server = Server::findOrFail($id);

       self::$params['headers']['API-Key'] = $server->apikey;
       self::$params['form_params']['SUBID'] = $server->apiserverid;

        $result = self::apiQuery('POST', self::$uri . 'server/start',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error'];

        return $result;
    }

    public static function powerOff($id)
    {
        $server = Server::findOrFail($id);

       self::$params['headers']['API-Key'] = $server->apikey;
       self::$params['form_params']['SUBID'] = $server->apiserverid;

        $result = self::apiQuery('POST', self::$uri . 'server/halt',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error'];

        return $result;
    }

    public static function shutDown($id) {
        return false;
    }

    public static function softReboot($id) {
        return false;
    }

    public static function hardReboot($id) {
        $server = Server::findOrFail($id);

       self::$params['headers']['API-Key'] = $server->apikey;
       self::$params['form_params']['SUBID'] = $server->apiserverid;

        $result = self::apiQuery('POST', self::$uri . 'server/reboot',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error'];

        return $result;
    }

    public static function reinstallOs($id, $template) {
        $server = Server::findOrFail($id);

       self::$params['headers']['API-Key'] = $server->apikey;
       self::$params['form_params']['SUBID'] = $server->apiserverid;
       self::$params['form_params']['OSID'] = $template;

        $result = self::apiQuery('POST', self::$uri . 'server/os_change',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error'];

        return $result;
    }

    public static function attachIso($id, $template) {
        $server = Server::findOrFail($id);

       self::$params['headers']['API-Key'] = $server->apikey;
       self::$params['form_params']['SUBID'] = $server->apiserverid;
       self::$params['form_params']['ISOID'] = $template;

        $result = self::apiQuery('POST', self::$uri . 'server/iso_attach',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error'];

        return $result;
    }

    public static function removeIso($id) {
        $server = Server::findOrFail($id);

       self::$params['headers']['API-Key'] = $server->apikey;
       self::$params['form_params']['SUBID'] = $server->apiserverid;

        $result = self::apiQuery('POST', self::$uri . 'server/iso_detach',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error'];

        return $result;
    }

    public static function enableRescue($id) {
        return false;
    }

    public static function disableRescue($id) {
        return false;
    }

    public static function resetRootPassword($id) {
        return false;
    }

    public static function handleErrors($e) {
        
        $result['error'] = $e->getResponse()->getBody()->getContents();
        return $result;
    }

}
