<?php


namespace HostJams\CpanelAPI\Components\Ftp;

use HostJams\CpanelAPI\Cpanel\CpanelInterface;

class Ftp implements FtpInterface
{

    private $cPanel;

    public function __construct(CpanelInterface $cPanel)
    {
        $this->cPanel = $cPanel;
        $this->cPanel->setModule("Ftp");
        $this->cPanel->useUAPI();
    }

    /**
     * @inheritDoc
     */
    public function add(string $username, string $password, array $options = array()): bool
    {

        $options = array_merge(array('user'=>$username, 'pass'=>$password), $options);
        $this->cPanel->add_ftp($options);
        return $this->cPanel->hasError();
    }

    /**
     * @inheritDoc
     */
    public function delete(string $username, bool $destroy, string $domain = ""): bool
    {
        // TODO: Implement delete() method.
    }

    /**
     * @inheritDoc
     */
    public function list(array $skipAccountTypes = array(), array $includeAccountTypes = array()): array
    {
        // TODO: Implement list() method.
    }

    /**
     * @inheritDoc
     */
    public function listSessions(): array
    {
        $this->cPanel->setOutputTypeToArray();
        return $this->cPanel->list_sessions()['data'];
    }
}