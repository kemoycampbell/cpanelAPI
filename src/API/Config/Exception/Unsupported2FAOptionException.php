<?php

namespace KemoyCampbell\CpanelAPI\API\Config\Exception;

/**
* This exception is thrown when a 2FA option is not supported with a certain AuthType.
 * For example, the 2FA option is only available with password auth type but not token and others.
 */
class Unsupported2FAOptionException extends \RuntimeException implements ConfigExceptionInterface
{

}
