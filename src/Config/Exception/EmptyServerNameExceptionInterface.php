<?php


namespace HostJams\CpanelAPI\Config\Exception;

/**
 * This class extends InvalidArgumentException and is thrown when the user
 * failed to supplied the server name
 * Class EmptyServerNameExceptionInterface
 * @package HostJams\CpanelAPI\Config\Exception
 */
class EmptyServerNameExceptionInterface extends \InvalidArgumentException implements ConfigExceptionInterface
{

}
