<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    //
    protected $fillable = [
        'name', 'hostname', 'ipv4', 'provider', 'provider_url', 'location', 'sshport', 'sshkey', 'type', 'apikey', 'apiurl', 'apipass', 'apiserverid'
    ];

    public static function rules($update = false, $id = null)
    {
        $commun = [
            'type'    => "required",
            'name'    => "required",
            'hostname'    => "required",
            'ipv4'    => "required",
            'sshport'    => "required",
            'provider'    => "required",
            'provider_url'    => "nullable|url",
            'location'    => "required",
            
        ];

        if ($update) {
            return $commun;
        }

        return array_merge($commun, [
            'type'    => "required",
            'name'    => "required",
            'hostname'    => "required",
            'ipv4'    => "required",
            'sshport'    => "required",
            'provider'    => "required",
            'provider_url'    => "nullable|url",
            'location'    => "required",
            
        ]);
    }
}


