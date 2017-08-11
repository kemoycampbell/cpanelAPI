<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/31/17
 * Time: 10:25 PM
 */
namespace hostjams\Cpanel\Config;


use hostjams\Cpanel\Exception\PasswordCannotBeEmpty;
use hostjams\Cpanel\Exception\ServerNameCannotBeEmpty;
use hostjams\Cpanel\Exception\Unsupport_ports;
use hostjams\Cpanel\Exception\UserNameCannotBeEmpty;

class Config
{
    private $server = null;
    private $username = null;
    private $password = null;
    private $port = 2083;
    private $support_ports = array(2082,2083,2095,2096,2086,2087);

    public function __construct(string $server,string $username,string $password,int $port=2083)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
    }

    public function getServer():string
    {
        if(empty($this->server))
            throw new ServerNameCannotBeEmpty();
        return $this->server;
    }

    public function getUsername():string
    {
        if(empty($this->username))
            throw new UserNameCannotBeEmpty();
        return $this->username;
    }

    public function getPassword():string
    {
        if(empty($this->password))
            throw new PasswordCannotBeEmpty();
        return $this->password;
    }

    public function getPort():int
    {
        if(empty($this->port) || !is_int($this->port) || !in_array($this->port,$this->support_ports))
            throw new Unsupport_ports("You supplied an invalid port. see https://documentation.cpanel.net/display/SDK/Guide+to+cPanel+API+2
            for all supported port");
        return $this->port;
    }

}