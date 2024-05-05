<?php



namespace KemoyCampbell\CpanelAPI\API\Config;

use KemoyCampbell\CpanelAPI\API\Config\Exception\ConfigPortException;
use KemoyCampbell\CpanelAPI\API\Config\Exception\ConfigServerDomainException;
use KemoyCampbell\CpanelAPI\API\Config\Exception\ConfigTokenException;
use KemoyCampbell\CpanelAPI\API\Config\Exception\ConfigUsernameException;

class CpanelConfig implements ConfigInterface
{
    private string $serverDomain;
    private string $username;
    private int $port;
    private string $token;

    public function __construct(string $serverDomain, string $username, string $token, int $port = 2083)
    {
        $this->serverDomain = $serverDomain;
        $this->username = $username;
        $this->port = $port;
        $this->token = $token;

        $this->validateConfiguration();
    }

    /**
     * @inheritDoc
     */
    public function getServerDomain(): string
    {
        return $this->serverDomain;
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
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @inheritDoc
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @inheritDoc
     */
    public function getSupportedCpanelPorts(): array
    {
        return [2082, 2083, 2086, 2087, 2095, 2096];
    }


    /**
     * Validates the server domain.
     * Throws a ConfigServerDomainException if the server domain is empty.
     */
    private function validateServerDomain(): void
    {
        if (empty($this->serverDomain)) {
            throw new ConfigServerDomainException('Server domain cannot be empty');
        }
    }

    /**
     * Validates the username.
     * Throws a ConfigUsernameException if the username is empty.
     */
    private function validateUsername(): void
    {
        if (empty($this->username)) {
            throw new ConfigUsernameException('Username cannot be empty');
        }
    }

    /**
     * Validates the token.
     * Throws a ConfigTokenException if the token is empty.
     */
    private function validateToken(): void
    {
        if (empty($this->token)) {
            throw new ConfigTokenException('Token cannot be empty');
        }
    }

    /**
     * Validates the port.
     * Throws a ConfigPortException if the port is not supported.
     */
    private function validatePort(): void
    {
        if (!in_array($this->port, $this->getSupportedCpanelPorts())) {
            $supportedPorts = implode(',', $this->getSupportedCpanelPorts());
            throw new ConfigPortException("{$this->port} is not supported. 
            The supported ports are {$supportedPorts}");
        }
    }

    /**
     * Validates the entire configuration.
     * Calls individual validation methods for each configuration parameter.
     * @throws ConfigServerDomainException, ConfigUsernameException, ConfigTokenException, ConfigPortException
     */
    private function validateConfiguration(): void
    {
        $this->validateServerDomain();
        $this->validateUsername();
        $this->validateToken();
        $this->validatePort();
    }
}
