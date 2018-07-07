<?php

namespace App\Libraries\API;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Client;

abstract class Provider {

    public static function apiQuery($method, $uri, $params) {
        $client = new Client(['base_uri' => $uri]); 
        
        try {
            if ($method == 'GET')
                $response = $client->get($uri, $params);
            else
                $response = $client->post($uri, $params);

            $result = $response->getBody()->getContents();
            $result = json_decode($result, true);
    
        }
        catch (BadResponseException $e)
        {
            $result = static::handleErrors($e);
        }
        
        return $result;
    }

    abstract public static function getServer($id);
    abstract public static function powerOn($id);
    abstract public static function powerOff($id);
    abstract public static function shutDown($id);
    abstract public static function softReboot($id);
    abstract public static function hardReboot($id);
    abstract public static function resetRootPassword($id);
    abstract public static function reinstallOs($id, $template);
    abstract public static function attachIso($id, $template);
    abstract public static function removeIso($id);
    abstract public static function enableRescue($id);
    abstract public static function disableRescue($id);
    abstract public static function handleErrors($e);

}
