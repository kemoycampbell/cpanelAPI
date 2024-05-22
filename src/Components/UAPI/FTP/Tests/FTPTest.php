<?php

namespace KemoyCampbell\CpanelAPI\Components\UAPI\FTP\Tests;

use KemoyCampbell\CpanelAPI\API\Config\AuthType;
use KemoyCampbell\CpanelAPI\API\Config\CpanelConfig;
use KemoyCampbell\CpanelAPI\API\CpanelAPI\CpanelAPI;
use KemoyCampbell\CpanelAPI\Components\UAPI\FTP\FTP;
use KemoyCampbell\CpanelAPI\Components\UAPI\FTP\FTPInterface;
use PHPUnit\Framework\TestCase;

class FTPTest extends TestCase
{
    private FTPInterface $ftp;
    private string $user;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $config = new CpanelConfig(
            $_ENV['CPANEL_DOMAIN'],
            $_ENV['CPANEL_USERNAME'],
            $_ENV['CPANEL_TOKEN'],
            \KemoyCampbell\CpanelAPI\API\Config\AuthType::Token
        );

        $api = new CpanelAPI($config);
        $this->user = "testFTPUser";
        $this->ftp = new FTP($api);
    }

    public function testAddFTP()
    {

        $password = "NorDakota1";
        $status = $this->ftp->createFTPAccount(
            $this->user,
            $disallowDot = true,
            $domain = '',
            $homedir = '',
            $password,
            $passwordHash = '',
            $quota = 0
        );

        $this->assertTrue($status);
    }

    public function testDeleteFTP()
    {
        //we want to destroy the home directory too if it was created
        $status = $this->ftp->delete($this->user, $destroy = true);

        $this->assertTrue($status);
    }

    public function testUpdateQuota()
    {
        $status = $this->ftp->updateQuota($this->user, $unlimited = false, $quota = 100);
        $this->assertTrue($status);
    }

    public function testUpdateWelcomeMessage()
    {
        $status = $this->ftp->updateWelcomeMessage("Welcome to the FTP server - Cpanel API Modulation");
        $this->assertTrue($status);

    }

    public function testGetAccounts()
    {
        $accounts = $this->ftp->getAccounts();
        $this->assertIsArray($accounts) && $this->assertTrue(count($accounts) > 0);
    }
}
