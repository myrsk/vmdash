<?php

namespace App\Libraries\API;

use App\Server;

class ScalewayPAR extends Provider {

    public static $uri = 'https://cp-par1.scaleway.com/';
    public static $params = [
        'headers'  => ['content-type' => 'application/json', 'Accept' => 'application/json']
    ];
    
    public static function getServer($id) {
        $server = Server::findOrFail($id);

        self::$params['headers']['X-Auth-Token'] = $server->apipass;

        $result = self::apiQuery('GET', self::$uri . 'servers/'.$server->apiserverid, self::$params);

        if (isset($result['error']))
           $array['errormessage'] = $result['error']['message'].' ('.$result['error']['type'].')';
        else {
            //values from DB
            $array['db_id'] = $server->id;
            $array['db_name'] = $server->name;
            $array['db_location'] = $server->location;
            $array['db_provider'] = $server->provider;
            $array['db_providerurl'] = $server->provider_url;
            $array['db_sshkey'] = $server->sshkey;
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
            elseif ($result['server']['state'] == 'stopped in place' || $result['server']['state'] == 'stopped')
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

    public static function powerOn($id) {
        $server = Server::findOrFail($id);

        self::$params['headers']['X-Auth-Token'] = $server->apipass;
        self::$params['json']['action'] = 'poweron';

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/action',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error']['message'].' ('.$result['error']['type'].')';

        return $result;
    }

    public static function powerOff($id)
    {
        $server = Server::findOrFail($id);

        self::$params['headers']['X-Auth-Token'] = $server->apipass;
        self::$params['json']['action'] = 'poweroff';

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/action',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error']['message'].' ('.$result['error']['type'].')';

        return $result;
    }

    public static function shutDown($id) {
        $server = Server::findOrFail($id);

        self::$params['headers']['X-Auth-Token'] = $server->apipass;
        self::$params['json']['action'] = 'stop_in_place';

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/action',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error']['message'].' ('.$result['error']['type'].')';

        return $result;
    }

    public static function softReboot($id) {
        $server = Server::findOrFail($id);

        self::$params['headers']['X-Auth-Token'] = $server->apipass;
        self::$params['json']['action'] = 'reboot';

        $result = self::apiQuery('POST', self::$uri . 'servers/'.$server->apiserverid.'/action',self::$params);

        if (isset($result['error']))
           $result['errormessage'] = $result['error']['message'].' ('.$result['error']['type'].')';

        return $result;
    }

    public static function hardReboot($id) {
        return false;
    }

    public static function reinstallOs($id, $template) {
        return false;
    }

    public static function attachIso($id, $template) {
        return false;
    }

    public static function removeIso($id) {
        return false;
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
        
        $e = $e->getResponse()->getBody()->getContents();
        $e = json_decode($e, true);
        $result['error'] = $e;
        return $result;
    }

}
