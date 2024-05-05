<?php

namespace Config;

use KemoyCampbell\CpanelAPI\API\Config\ConfigInterface;
use KemoyCampbell\CpanelAPI\API\Config\CpanelConfig;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private string $serverDomain;
    private string $username;
    private int $port;
    private string $token;
    private ConfigInterface $config;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->serverDomain = 'example.com';
        $this->port = 2083;
        $this->username = 'username';
        $this->token = 'token';

        $this->config = new CpanelConfig($this->serverDomain, $this->username, $this->token, $this->port);
    }

    public function testCpanelUsername()
    {
        $this->assertEquals($this->username, $this->config->getUsername());
    }

    public function testCpanelServerDomain()
    {
        $this->assertEquals($this->serverDomain, $this->config->getServerDomain());
    }

    public function testCpanelPort()
    {
        $this->assertEquals($this->port, $this->config->getPort());
    }

    public function testCpanelToken()
    {
        $this->assertEquals($this->token, $this->config->getToken());
    }

    public function testCpanelPortException()
    {
        $this->expectException(\KemoyCampbell\CpanelAPI\API\Config\Exception\ConfigPortException::class);
        $this->config = new CpanelConfig($this->serverDomain, $this->username, $this->token, 0);
    }

    public function testCpanelServerDomainException()
    {
        $this->expectException(\KemoyCampbell\CpanelAPI\API\Config\Exception\ConfigServerDomainException::class);
        $this->config = new CpanelConfig('', $this->username, $this->token, $this->port);
    }

    public function testCpanelUsernameException()
    {
        $this->expectException(\KemoyCampbell\CpanelAPI\API\Config\Exception\ConfigUsernameException::class);
        $this->config = new CpanelConfig($this->serverDomain, '', $this->token, $this->port);
    }

    public function testCpanelTokenException()
    {
        $this->expectException(\KemoyCampbell\CpanelAPI\API\Config\Exception\ConfigTokenException::class);
        $this->config = new CpanelConfig($this->serverDomain, $this->username, '', $this->port);
    }


}
