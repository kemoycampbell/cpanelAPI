<?php

namespace KemoyCampbell\CpanelAPI\Components\UAPI\DNS;

interface DNSInterface
{
    /**
     *This function indicates whether the account's domains resolve exclusively to this server.
     * @param array $domains - An array of domains to check
     * note for implementation: domain=domain=example.com&domain=example1.com&domain=example2.com
     */
    public function getDomainsResolveLocallyStatus(array $domains): array;

    /**
     * This function checks whether the local server is authoritative for the domain's DNS records.
    * @param array $domains
    * @return array
     */
    public function getDomainsLocalDNSAuthoritativeStatus(array $domains): array;

    /**
     * This function returns DNS zone information about a domain.
    * @param string $domain
    * @return array
     */
    public function getDNSZoneInformation(string $domain):array;

    public function getParsedDNSZone(string $zone);
}