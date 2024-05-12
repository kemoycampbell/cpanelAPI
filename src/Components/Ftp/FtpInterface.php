<?php


namespace HostJams\CpanelAPI\Components\Ftp;


interface FtpInterface
{
    /**
     * This function creates an FTP account
     * @param string $username The FTP account's username
     * @param string $password The new password
     * @param array $options additonal array of parameters such as domain, quota, homedir etc
     * @link https://documentation.cpanel.net/display/DD/cPanel+API+2+Functions+-+Ftp%3A%3Aaddftp
     * @return bool true if successful added. False otherwise
     */
    public function add(string $username, string $password, array $options = array()):bool;

    /**
     * This function deletes an FTP account
     * @param string $username The FTP account'username
     * @param bool $destroy whether to delete the account's FTP directory
     * @param string $domain the user's associated domain
     * @return bool true if successful delete. False otherwise
     */
    public function delete(string $username, bool $destroy, string $domain = ""):bool;


    /**
     * @param array $skipAccountTypes A list of the FTP account types to exclude from the function's results.
     * If you do not specify this parameter, this function does not exclude any account types.
     * @param array $includeAccountTypes A list of the FTP account types to include in the function's results.
     * If you do not specify this parameter, this function returns all FTP account types.
     * @return array an array contain the type of ftp account, homedir and user of each ftp accounts
     * @link https://documentation.cpanel.net/display/DD/UAPI+Functions+-+Ftp%3A%3Alist_ftp
     */
    public function list(array $skipAccountTypes = array(), array $includeAccountTypes = array()):array;

    /**This function lists the FTP server's active sessions.
     * @return array containing the pid, status, file, user cmdline, host and login of the active sessions
     * @link https://documentation.cpanel.net/display/DD/UAPI+Functions+-+Ftp%3A%3Alist_sessions
     */
    public function listSessions():array;

}