<?php

namespace KemoyCampbell\CpanelAPI\API\tests\OPT;

use KemoyCampbell\CpanelAPI\API\OTP\OTP;
use PHPUnit\Framework\TestCase;

class OPTTest extends TestCase
{
    private string $secret;
    private string $hashAlgorithm;
    private int $securityCodeLength;
    private OTP $opt;


    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->secret = 'secret';
        $this->hashAlgorithm = 'sha1';
        $this->securityCodeLength = 6;

        $this->opt = new OTP($this->secret, $this->hashAlgorithm, $this->securityCodeLength);
    }

    public function testSecret()
    {
        $this->assertEquals($this->secret, $this->otp->getSecret());
    }

}