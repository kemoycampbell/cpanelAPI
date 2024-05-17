<?php

namespace KemoyCampbell\CpanelAPI\Components\UAPI\FTP;

interface FTPInterface
{
    /**
     * Create FTP account.
     *
     * This function creates an FTP account with the specified parameters.
     *
     * @param string $user The username for the FTP account.
     * @param bool $disallowDot Whether to disallow dots (.) in the username.
     *                          Defaults to true.
     * @param string $domain The domain associated with the FTP account.
     *                        Defaults to an empty string, indicating the cPanel account's primary domain.
     * @param string $homedir The root directory for the FTP account, relative to the cPanel account's home directory.
     *                        Defaults to an empty string, indicating a directory with the same name as the FTP account.
     * @param string $password The password for the FTP account.
     *                         Defaults to an empty string.
     * @param string $passwordHash The password hash for the FTP account.
     *                              Defaults to an empty string.
     * @param int $quota The maximum disk usage quota for the FTP account, in megabytes (MB).
     *                   A value of 0 indicates unlimited disk space.
     *                   Defaults to 0.
     *
     * @return bool True if the FTP account was successfully created, false otherwise.
     */
    public function createFTPAccount(
        string $user,
        bool $disallowDot = true,
        string $domain = '',
        string $homedir = '',
        string $password = '',
        string $passwordHash = '',
        int $quota = 0
    ): bool;

    /**
     * This function deletes an FTP account.
     *
     * @param string $user The FTP account's username.
     * @param bool $destroy Whether to delete the account's FTP directory.
     * @param string $domain The user's associated domain.
     *
     * @return bool True if successfully deleted, false otherwise.
     */
    public function delete(string $user, bool $destroy = false, string $domain = ''): bool;

    /**
     * This function checks whether an FTP account exists.
    * @param string $user The username for the FTP account.
    * @param string $domain The domain associated with the FTP account.
    * @return bool True if the FTP account exists, false otherwise.
     */
    public function exist(string $user, string $domain = ""):bool;

    /** This function returns the quota for an FTP account.
     * @param string $user The username for the FTP account.
     * @param string $domain The domain associated with the FTP account.
     * @return int The quota for the FTP account, in megabytes (MB).
     */
    public function getQuota(string $user, string $domain = ""):int;

    /** This function updates the quota for an FTP account.
     * @param string $user The username for the FTP account.
     * @param bool $unlimited Whether to set the quota to unlimited.
     * @param int $quota The new quota for the FTP account, in megabytes (MB).
     * @param string $domain The domain associated with the FTP account.
     * @return bool True if the quota was successfully updated, false otherwise.
     */
    public function updateQuota(string $user, bool $unlimited = false, int $quota = 0, string $domain = ""):bool;

    /** This function returns the FTP welcome message banner
     * @return string The FTP welcome message banner.
     */
    public function getWelcomeMessage():string;

    /** This function updates the FTP welcome message banner.
     * @param string $message The new FTP welcome message banner.
     * @return bool True if the FTP welcome message banner was successfully updated, false otherwise.
     */
    public function updateWelcomeMessage(string $message):bool;


    /**
     * Get FTP accounts.
     *
     * Retrieves a list of FTP accounts filtered by account types.
     *
     * @param array $includeAccountTypes An array of account types to include in the result.
     *                                   Defaults to an empty array, indicating all account types.
     * @param array $excludeAccountTypes An array of account types to exclude from the result.
     *                                   Defaults to an empty array, indicating no exclusion.
     * @return array An array containing FTP account information.
     */
    public function getAccounts(array $includeAccountTypes = [], array $excludeAccountTypes = []): array;


    /**
     * Get FTP accounts disk usage.
     *
     * Retrieves disk usage information for FTP accounts filtered by account types.
     *
     * @param array $includeAccountTypes An array of account types to include in the result.
     *                                   Defaults to an empty array, indicating all account types.
     * @param array $excludeAccountTypes An array of account types to exclude from the result.
     *                                   Defaults to an empty array, indicating no exclusion.
     * @return array An array containing disk usage information for FTP accounts.
     */
    public function getAccountsDiskUsage(array $includeAccountTypes = [], array $excludeAccountTypes = []): array;


    /**
     * Update FTP account password.
     *
     * Updates the password for the specified FTP account.
     *
     * @param string $user The username of the FTP account.
     * @param string $password The new password for the FTP account.
     * @param string $domain The domain associated with the FTP account. Defaults to an empty string.
     * @return bool True if the password was successfully updated, false otherwise.
     */
    public function updatePassword(string $user, string $password, string $domain = ""): bool;


    /**
     * Update FTP account home directory.
     *
     * Updates the home directory for the specified FTP account.
     *
     * @param string $user The username of the FTP account.
     * @param string $homeDirectory The new home directory for the FTP account.
     * @param string $domain The domain associated with the FTP account. Defaults to an empty string.
     * @return bool True if the home directory was successfully updated, false otherwise.
     */
    public function updateHomeDirectory(string $user, string $homeDirectory, string $domain = ""): bool;

}
