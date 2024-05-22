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

        $this->ftp = new FTP($api);
    }

    public function testAddFTP()
    {
        $user = "testFTPUser";
        $password = "NorDakota1";
        $status = $this->ftp->createFTPAccount(
            $user,
            $disallowDot = true,
            $domain = '',
            $homedir = '',
            $password,
            $passwordHash = '',
            $quota = 0
        );

        $this->assertTrue($status);
    }
}
