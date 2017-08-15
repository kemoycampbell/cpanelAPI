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
use hostjams\Cpanel\Exception\UnSupportedPort;
use hostjams\Cpanel\Exception\UserNameCannotBeEmpty;

/**
 * Class Config
 * @package hostjams\Cpanel\Config
 */
class Config
{
    private $server = null;
    private $username = null;
    private $password = null;
    private $port = 2083;
    private $supportedPorts = array(2082,2083,2095,2096,2086,2087);

    public function __construct(string $server, string $username, string $password, int $port = 2083)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;

        $this->validateConfig();
    }

    public function getServer():string
    {
        return $this->server;
    }

    public function getUsername():string
    {
        return $this->username;
    }

    public function getPassword():string
    {
        return $this->password;
    }

    public function getPort():int
    {

        return $this->port;
    }


    /**
     * This function checks the setting of the configuration.
     * @throws PasswordCannotBeEmpty - throws if the password is empty
     * @throws ServerNameCannotBeEmpty - throws if the server or hostname is empty
     * @throws UnSupportedPort
     * @throws UserNameCannotBeEmpty
     */
    private function validateConfig()
    {
        if (empty($this->port) || !is_int($this->port) || !in_array($this->port, $this->supportedPorts)) {
            throw new UnSupportedPort("You supplied an invalid port. see 
            https://documentation.cpanel.net/display/SDK/Guide+to+cPanel+API+2
            for all supported port");
        }

        if (empty($this->password)) {
            throw new PasswordCannotBeEmpty();
        }

        if (empty($this->username)) {
            throw new UserNameCannotBeEmpty();
        }

        if (empty($this->server)) {
            throw new ServerNameCannotBeEmpty();
        }
    }

}