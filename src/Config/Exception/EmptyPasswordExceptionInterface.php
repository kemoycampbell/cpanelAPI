<?php


namespace HostJams\CpanelAPI\Config\Exception;

use HostJams\CpanelAPI\Config\ConfigInterface;

/**
 * This class extends the InvalidArgumentException and is thrown when the password to connect
 * to the cpanel is not supplied
 * Class EmptyPasswordExceptionInterface
 * @package HostJams\CpanelAPI\Config\Exception
 */
class EmptyPasswordExceptionInterface extends \InvalidArgumentException implements ConfigExceptionInterface
{

}