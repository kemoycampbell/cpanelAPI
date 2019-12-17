<?php

namespace HostJams\CpanelAPI\Config\Exception;

/**
 * This exception extends the InvalidArgumentException and is
 * thrown when a user attempted to connect to the cpanel via an
 * supported port.
 * Class UnSupportedPortExceptionInterface
 * @package HostJams\CpanelAPI\Config\Exception
 */
class UnSupportedPortExceptionInterface extends \InvalidArgumentException implements ConfigExceptionInterface
{

}