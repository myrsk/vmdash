<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 1-10-18
 * Time: 13:20
 */

namespace App\Libraries\API;

use App\Server;


class DigitalOcean extends Provider {

    public static $uri = 'https://api.digitalocean.com/v2/';
    public static $params = [
        'headers'  => ['Accept' => 'application/json', 'Content-Type' => 'application/json']
    ];

    public static function getServer($id) {
        $server = Server::findOrFail($id);

        self::$params['headers']['Authorization'] = 'Bearer ' . $server->apikey;

        $result = self::apiQuery('GET', self::$uri . 'droplets/' . $server->apiserverid, self::$params);
        $result_images = self::apiQuery('GET', self::$uri . 'images?page=1&per_page=1000', self::$params);

        if (isset($result['error'])) {
            $array['errormessage'] = json_decode($result['error'])->message;
        } else {
            $result = $result['droplet'];

            $array['db_id'] = $server->id;
            $array['db_name'] = $server->name;
            $array['db_location'] = $server->location;
            $array['db_provider'] = $server->provider;
            $array['db_providerurl'] = $server->provider_url;
            $array['db_sshkey'] = $server->sshkey;

            $internalIP = null;
            foreach($result['networks']['v4'] as $network) {
                if($network['type'] == 'private') {
                    $internalIP = $network['ip_address'];
                }
            }

            $array['api_model'] = $result['size']['slug'];
            $array['api_hostname'] = $result['name'];
            $array['api_os'] = $result['image']['distribution'] . ' ' . $result['image']['name'];
            $array['api_internalip'] = $internalIP;
            $array['api_ipv4'] = $result['networks']['v4'][0]['ip_address'];
            $array['api_ipv6'] = $result['networks']['v6'][0]['ip_address'];
            $array['api_datacenter'] = $result['region']['name'] .' (' . strtoupper($result['region']['slug']) .')';
            $array['api_totalcores'] = $result['vcpus'];
            $array['api_directvncurl'] = null;
            $array['api_totalmemory'] = formatSize((preg_replace('/[^0-9.]+/', '', $result['memory'])*pow(1024,2)),$decimals=2); // from mb to bytes
            $array['api_totaldisk'] = formatSize((preg_replace('/[^0-9.]+/', '', $result['disk'])*pow(1024,3)),$decimals=2); // from gb to bytes
//            $array['api_totalbw'] = formatSize(($result['allowed_bandwidth_gb']*pow(1024,3)),$decimals=2); //from gb to bytes
//            $array['api_usedbw'] = formatSize(($result['current_bandwidth_gb']*pow(1024,3)),$decimals=2); //from gb to bytes
//            $array['api_usedbwpercent'] = ($result['current_bandwidth_gb'] / 100) * $result['allowed_bandwidth_gb'];

            $array['api_totalbw'] = 0;
            $array['api_usedbw'] = 0;
            $array['api_usedbwpercent'] = 0;

            if ($result['status'] == 'active')
                $array['api_status'] = 'online';
            elseif ($result['status'] == 'off')
                $array['api_status'] = 'offline';
            else
                $array['api_status'] = $result['status'];

            $result_images = $result_images['images'];
            $array['api_images'] = array_map(function($result_images) {
                return array(
                    'id' => $result_images['id'],
                    'name' => $result_images['distribution'] . ' ' . $result_images['name']
                );
            }, $result_images);

            $array['api_isos'] = [];

            $array['action_softreboot'] = false;
            $array['action_hardreboot'] = true;
            $array['action_shutdown'] = true;
            $array['action_poweroff'] = true;
            $array['action_poweron'] = true;
            $array['action_rootpasswordreset'] = false;
            $array['action_enablerescue'] = false;
            $array['action_disablerescue'] = false;
            $array['action_reinstallos'] = true;
            $array['action_directvnc'] = false;
            $array['action_attachiso'] = false;
            $array['action_removeiso'] = false;
        }

        return $array;
    }

    private static function executeAction($id, $args) {
        $server = Server::findOrFail($id);

        self::$params['headers']['Authorization'] = "Bearer " . $server->apikey;
        self::$params['json'] = $args;

        $result = self::apiQuery('POST', self::$uri . 'droplets/'.$server->apiserverid.'/actions',self::$params);

        if (isset($result['error'])) {
            $result['errormessage'] = json_decode($result['error'])->message;
            var_dump($result); exit();
        }

        return $result;
    }

    public static function powerOn($id) {
        return self::executeAction($id, ['type' => 'power_on']);
    }

    public static function powerOff($id) {
        return self::executeAction($id, ['type' => 'power_off']);
    }

    public static function shutDown($id) {
        return self::executeAction($id, ['type' => 'shutdown']);
    }

    public static function softReboot($id) {
        return false;
    }

    public static function hardReboot($id) {
        return self::executeAction($id, ['type' => 'power_cycle']);
    }

    public static function reinstallOs($id, $template) {
        return self::executeAction($id, ['type' => 'rebuild', 'image' => $template]);
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
        $result['error'] = $e->getResponse()->getBody()->getContents();
        return $result;
    }

}
