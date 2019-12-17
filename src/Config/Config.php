<?php


namespace HostJams\CpanelAPI\Config;

use HostJams\CpanelAPI\Config\Exception\EmptyPasswordExceptionInterface;
use HostJams\CpanelAPI\Config\Exception\EmptyServerNameExceptionInterface;
use HostJams\CpanelAPI\Config\Exception\EmptyUsernameExceptionInterface;
use HostJams\CpanelAPI\Config\Exception\UnSupportedPort;
use HostJams\CpanelAPI\Config\Exception\UnSupportedPortExceptionInterface;

/**
 * This class setup the configuration for the cpanel
 * Class Config
 * @package HostJams\CpanelAPI\Config
 */
class Config implements ConfigInterface
{
    /**
     * @var string The server name - ip or domain
     */
    private $serverName;

    /**
     * @var string  The username which is use for authenticate
     */
    private $username;

    /**
     * @var string The password which is use for authenticate
     */
    private $password;

    /**
     * @var int The cpanel port
     */
    private $port;


    /**
     * Config constructor - This function takes the parameters and configured the cpanel information.
     * The validateConfiguration function is then called which ensure that the required config information are supplied.
     * If the requirements are not met, an ConfigExceptionInterface is thrown.
     * @param string $serverName the server name - ip or domain
     * @param string $username the cpanel username
     * @param string $password the cpanel password
     * @param int $port the cpanel port
     */
    public function __construct(string $serverName, string $username, string $password, int $port = 2083)
    {
        $this->serverName = $serverName;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;

        $this->validateConfiguration();
    }

    /**
     * @inheritDoc
     */
    public function getServerName(): string
    {
        return $this->serverName;
    }

    /**
     * @inheritDoc
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @inheritDoc
     */
    public function getSupportedPorts(): array
    {
        return array(2082,2083,2095,2096,2086,2087);
    }

    /**
     * @inheritDoc
     */
    public function validateConfiguration(): void
    {
        //ensure that we are using a valid port
        if (!in_array($this->port, $this->getSupportedPorts())) {
            throw new UnSupportedPortExceptionInterface("Invalid Cpanel config port supplied. see 
            https://documentation.cpanel.net/display/SDK/Guide+to+cPanel+API+2
            for all supported port");
        }

        //ensure that the server name is not empty
        if (empty($this->serverName)) {
            throw new EmptyServerNameExceptionInterface("Cpanel config server name cannot be empty");
        }

        if (empty($this->username)) {
            throw new EmptyUsernameExceptionInterface("Cpanel username cannot be empty");
        }
        //ensure password is not empty
        if (empty($this->password)) {
            throw new EmptyPasswordExceptionInterface("Cpanel config password cannot be empty");
        }
    }
}
