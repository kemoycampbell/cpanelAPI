<?php

namespace Config;

use HostJams\CpanelAPI\Config\Config;
use HostJams\CpanelAPI\Config\Exception\EmptyUsernameExceptionInterface;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private $serverName;
    private $port;
    private $username;
    private $password;
    private $supportedPort = array(2082,2083,2095,2096,2086,2087);
    private $config;


    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->serverName = "dummyServer";
        $this->port = 2083;
        $this->username = "dummy";
        $this->password = "dummy";

        $this->config = new Config($this->serverName, $this->username, $this->password, $this->port);
        parent::__construct($name, $data, $dataName);
    }


    public function testGetSupportedPorts()
    {
        self::assertEquals($this->supportedPort, $this->config->getSupportedPorts());
    }

    public function testGetServerName()
    {
        self::assertEquals($this->serverName, $this->config->getServerName());
    }

//    public function testValidateConfiguration()
//    {
//        self
//    }

    public function testGetUsername()
    {
        self::assertEquals($this->username, $this->config->getUsername());
    }

    public function testGetPort()
    {
        self::assertEquals($this->port, $this->config->getPort());
    }

    public function testGetPassword()
    {
        self::assertEquals($this->password, $this->config->getPassword());
    }
}
