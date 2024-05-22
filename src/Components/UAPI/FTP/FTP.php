<?php

namespace KemoyCampbell\CpanelAPI\Components\UAPI\FTP;

use KemoyCampbell\CpanelAPI\API\CpanelAPI\CPanelAPIInterface;
use KemoyCampbell\CpanelAPI\Components\UAPI\FTP\Exception\CpanelFTPException;
use KemoyCampbell\CpanelAPI\Components\UAPI\FTP\FTPInterface;

class FTP implements FTPInterface
{
    private string $module;
    private CPanelAPIInterface $api;

    public function __construct(CPanelAPIInterface $api)
    {
        $this->module = 'Ftp';
        $this->api = $api;
    }

    /**
     * @inheritDoc
     * @throws CpanelFTPException
     */
    public function createFTPAccount(
        string $user,
        bool $disallowDot = true,
        string $domain = '',
        string $homedir = '',
        string $password = '',
        string $passwordHash = '',
        int $quota = 0
    ): bool {

        $function = 'add_ftp';

        $parameters = array(
            "user" => $user,
            "disallowdot" => $disallowDot ? 1:0,
            "quota" => $quota,
        );

        if ($domain !== '') {
            $parameters['domain'] = $domain;
        }

        if ($homedir !== '') {
            $parameters['homedir'] = $homedir;
        }

        if ($password !== '') {
            $parameters['pass'] = $password;
        }

        if ($passwordHash !== '') {
            $parameters['pass_hash'] = $passwordHash;
        }

        print_r($parameters);

        $response = $this->api->request($this->module, $function, $parameters);

        if ($this->api->hasErrors()) {
            throw new CpanelFTPException($this->api->getErrors());
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $user, bool $destroy = false, string $domain = ''): bool
    {
        $function = 'delete_ftp';
        $parameters = array(
            "user" => $user,
            "destroy" => $destroy ? 1:0,
        );

        if (!empty($domain)) {
            $parameters['domain'] = $domain;
        }

        $response = $this->api->request($this->module, $function, $parameters);
        if ($this->api->hasErrors()) {
            throw new CpanelFTPException($this->api->getErrors());
        }

        return true;
        // TODO: Implement delete() method.
    }

    /**
     * @inheritDoc
     */
    public function exist(string $user, string $domain = ""): bool
    {
        // TODO: Implement exist() method.
    }

    /**
     * @inheritDoc
     */
    public function getQuota(string $user, string $domain = ""): int
    {
        // TODO: Implement getQuota() method.
    }

    /**
     * @inheritDoc
     */
    public function updateQuota(string $user, bool $unlimited = false, int $quota = 0, string $domain = ""): bool
    {
        // TODO: Implement updateQuota() method.
    }

    /**
     * @inheritDoc
     */
    public function getWelcomeMessage(): string
    {
        // TODO: Implement getWelcomeMessage() method.
    }

    /**
     * @inheritDoc
     */
    public function updateWelcomeMessage(string $message): bool
    {
        // TODO: Implement updateWelcomeMessage() method.
    }

    /**
     * @inheritDoc
     */
    public function getAccounts(array $includeAccountTypes = [], array $excludeAccountTypes = []): array
    {
        // TODO: Implement getAccounts() method.
    }

    /**
     * @inheritDoc
     */
    public function getAccountsDiskUsage(array $includeAccountTypes = [], array $excludeAccountTypes = []): array
    {
        // TODO: Implement getAccountsDiskUsage() method.
    }

    /**
     * @inheritDoc
     */
    public function updatePassword(string $user, string $password, string $domain = ""): bool
    {
        // TODO: Implement updatePassword() method.
    }

    /**
     * @inheritDoc
     */
    public function updateHomeDirectory(string $user, string $homeDirectory, string $domain = ""): bool
    {
        // TODO: Implement updateHomeDirectory() method.
    }
}
