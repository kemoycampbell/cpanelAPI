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

        //print_r($parameters);

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
    }

    /**
     * @inheritDoc
     */
    public function exist(string $user, string $domain = ""): bool
    {
        //assign the right function
        $function = 'ftp_exists';
        //create the parameters. user is required
        $parameters = array("user" => $user);

        //we will add domain as parameter if the user supplies it(not empty)
        if (!empty($domain)) {
            $parameters['domain'] = $domain;
        }

        $response = $this->api->request($this->module, $function, $parameters);
        //we do not want to throw an exception if the error is about user doesnt exsit. we want to return false instead
        if ($this->api->hasErrors()) {
            //if doesnt exist is part of the error from the cpanel then we return false as the user doesnt exist
            if (strpos($this->api->getErrors(), "does not exist")!==false) {
                return false;
            }
        }

        //we still have error and it is not related to the user doesnt exist, alert the developer
        if ($this->api->hasErrors()) {
            throw new CpanelFTPException($this->api->getErrors());
        }

        //otherwise the user exist.
        return $response->getResponseAsArray()["status"] == 1;
    }

    /**
     * @inheritDoc
     */
    public function getQuota(string $user, string $domain = ""): int
    {
       //get the total quota for a specific user.

        //if there are any error, it should be thrown

        //the domain is not required unless the user supplied it
    }

    /**
     * @inheritDoc
     */
    public function updateQuota(string $user, bool $unlimited = false, int $quota = 0, string $domain = ""): bool
    {
        $function = 'set_quota';
        $parameters = array(
            "user"=>$user
        );
        //user which to disable the quota and make it unlimited
        if ($unlimited) {
            $parameters['kill'] = 1;
        }

        //user wish to assign a specific quota
        if ($quota > 0) {
            $parameters['quota'] = $quota;
        }

        //user wish to apply this to a specific domain
        if ($domain) {
            $parameters['domain'] = $domain;
        }

        //make the request
        $this->api->request($this->module, $function, $parameters);

        //throw an exception if any error occurred
        if ($this->api->hasErrors()) {
            throw new CpanelFTPException($this->api->getErrors());
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getWelcomeMessage(): string
    {
       // returns the welcome message from the ftp
    }

    /**
     * @inheritDoc
     */
    public function updateWelcomeMessage(string $message): bool
    {
        $parameter = array("message"=> $message);
        $function = "set_welcome_message";
        $this->api->request($this->module, $function, $parameter);

        if ($this->api->hasErrors()) {
            throw new CpanelFTPException($this->api->getErrors());
        }

        return true;
    }

    private function getFTPAccountData(
        bool $includeDiskUsage = false,
        array $includeAccountTypes = [],
        array $excludeAccountTypes = []
    ): array {

        $function = "list_ftp";
        if ($includeDiskUsage) {
            $function = "list_ftp_with_disk";
        }

        $parameters = array();
        if (count($includeAccountTypes) > 0) {
            $parameters['include_acct_types'] = $includeAccountTypes;
        }

        if (count($excludeAccountTypes) > 0) {
            $parameters['skip_acct_types'] = $excludeAccountTypes;
        }

        $response = $this->api->request($this->module, $function, $parameters);

        if ($this->api->hasErrors()) {
            throw new CpanelFTPException($this->api->getErrors());
        }

        return $response->getResponseAsArray()["data"];
    }

    /**
     * @inheritDoc
     * @throws CpanelFTPException
     */
    public function getAccounts(array $includeAccountTypes = [], array $excludeAccountTypes = []): array
    {
        return $this->getFTPAccountData($includeDiskUsage = false);
    }


    /**
     * @inheritDoc
     * @throws CpanelFTPException
     */
    public function getAccountsDiskUsage(array $includeAccountTypes = [], array $excludeAccountTypes = []): array
    {
        return $this->getFTPAccountData($includeDiskUsage = true);
    }

    /**
     * @inheritDoc
     */
    public function updatePassword(string $user, string $password, string $domain = ""): bool
    {
        $function = 'passwd';
        $parameters = array(
            "user" => $user,
            "pass" => $password
        );

        if (!empty($domain)) {
            $parameters['domain'] = $domain;
        }

        $this->api->request($this->module, $function, $parameters);

        if ($this->api->hasErrors()) {
            throw new CpanelFTPException($this->api->getErrors());
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function updateHomeDirectory(string $user, string $homeDirectory, string $domain = ""): bool
    {
        $function = 'set_homedir';
        $parameters = array(
           "user" => $user,
           "homedir" => $homeDirectory
        );

        if (!empty($domain)) {
            $parameters['domain'] = $domain;
        }

        $this->api->request($this->module, $function, $parameters);
        if ($this->api->hasErrors()) {
            throw new CpanelFTPException($this->api->getErrors());
        }

        return true;
    }
}
