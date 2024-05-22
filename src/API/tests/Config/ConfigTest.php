<?php

namespace KemoyCampbell\CpanelAPI\API\tests\Config;

use KemoyCampbell\CpanelAPI\API\Config\AuthType;
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

        $this->config = new CpanelConfig($this->serverDomain, $this->username, $this->token);
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

    public function testUseTwoFactorAuthentication()
    {
        $this->config = new CpanelConfig($this->serverDomain, $this->username, $this->token, AuthType::Password);
        $this->config->setTwoFactorAuthenticationSecret('secret');
        $this->assertTrue($this->config->useTwoFactorAuthentication());
    }

    public function testCpanelPortException()
    {
        $this->expectException(\KemoyCampbell\CpanelAPI\API\Config\Exception\ConfigPortException::class);
        $this->config->setPort(0);
    }

    public function testCpanelServerDomainException()
    {
        $this->expectException(\KemoyCampbell\CpanelAPI\API\Config\Exception\ConfigServerDomainException::class);
        $this->config = new CpanelConfig('', $this->username, $this->token);
    }

    public function testCpanelUsernameException()
    {
        $this->expectException(\KemoyCampbell\CpanelAPI\API\Config\Exception\ConfigUsernameException::class);
        $this->config = new CpanelConfig($this->serverDomain, '', $this->token);
    }

    public function testCpanelTokenException()
    {
        $this->expectException(\KemoyCampbell\CpanelAPI\API\Config\Exception\ConfigTokenException::class);
        $this->config = new CpanelConfig($this->serverDomain, $this->username, '');
    }

}
