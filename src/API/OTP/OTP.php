<?php

namespace KemoyCampbell\CpanelAPI\API\OTP;

class OTP
{
    /**
     * @var string The base32 encoded secret key
     */
    public $secret;

    /**
     * @var string The algorithm used for the HMAC hash function
     */
    public $hashAlgorithm;

    /**
     * @var int The number of digits in the one-time password
     */
    public $securityCodeLength;

    /**
     * Constructor for the OTP class
     *
     * @param string $secret The base32 encoded secret key
     * @param string $hashAlgorithm The algorithm used for the HMAC hash function (default: 'sha1')
     * @param int $securityCodeLength The number of digits in the one-time password (default: 6)
     */
    public function __construct($secret, $hashAlgorithm = 'sha1', $securityCodeLength = 6)
    {
        $this->secret = $secret;
        $this->hashAlgorithm = $hashAlgorithm;
        $this->securityCodeLength = $securityCodeLength;
    }

    /**
     * Generate a one-time password
     *
     * @param int $timestamp The timestamp to use for generating the OTP
     * @return int The generated one-time password
     */
    public function generateOTP(int $timestamp = -1):int
    {
        // Get the counter value based on the provided or current timestamp
        $counter = $this->counter($timestamp);

        // Generate the HMAC hash using the provided secret key and counter value
        $hash = hash_hmac($this->hashAlgorithm, $this->intToBytestring($counter), $this->byteSecret(), true);

        // Extract 4 bytes from the hash starting from a specific offset, and convert to an integer
        $code = unpack('N', substr($hash, ord($hash[19]) & 0xf, 4))[1];

        // Mask the most significant bit to ensure the integer is positive
        $code &= 0x7fffffff;

        // Reduce the integer to the range of possible security codes
        $code %= pow(10, $this->securityCodeLength);

        // Format the code to the desired length and return
        $code = str_pad($code, $this->securityCodeLength, '0', STR_PAD_LEFT);
        return (int)$code;
    }


    /**
     * Get the counter value based on the provided or current timestamp
     *
     * @param int $timestamp The timestamp to use for generating the counter value
     * @return int The counter value
     */
    private function counter(int $timestamp = -1): int
    {
        if ($timestamp === -1) {
            $timestamp = time();
        }
        return (int)(($timestamp * 1000) / (30 * 1000));
    }

    /**
     * Decode the base32 secret into binary
     *
     * @return string|false The binary representation of the secret or false if decoding fails
     */
    public function byteSecret()
    {
        // Base32 to binary mapping
        $base32Map = [
            'A' => '00', 'B' => '01', 'C' => '02', 'D' => '03', 'E' => '04', 'F' => '05', 'G' => '06', 'H' => '07',
            'I' => '08', 'J' => '09', 'K' => '10', 'L' => '11', 'M' => '12', 'N' => '13', 'O' => '14', 'P' => '15',
            'Q' => '16', 'R' => '17', 'S' => '18', 'T' => '19', 'U' => '20', 'V' => '21', 'W' => '22', 'X' => '23',
            'Y' => '24', 'Z' => '25', '2' => '26', '3' => '27', '4' => '28', '5' => '29', '6' => '30', '7' => '31'
        ];

        // Check for valid padding
        $paddingCharCount = substr_count($this->secret, '=');
        $allowedValues = [6, 4, 3, 1, 0];
        if (!in_array($paddingCharCount, $allowedValues)) {
            throw new \RuntimeException('Invalid padding');
        }

        // Remove padding characters and split input
        $input = str_replace('=', '', $this->secret);
        $input = str_split($input);
        $binaryString = "";

        // Convert Base32 to binary
        for ($i = 0; $i < count($input); $i += 8) {
            $binaryChunk = "";
            // Check if input character is in the mapping
            if (!isset($base32Map[$input[$i]])) {
                throw new \RuntimeException('Invalid Base32 character');
            }
            // Convert each character to binary
            for ($j = 0; $j < 8; $j++) {
                $binaryChunk .= str_pad(base_convert($base32Map[$input[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
            }
            // Split binary chunk into bytes and convert to ASCII characters
            $byteChunks = str_split($binaryChunk, 8);
            foreach ($byteChunks as $byte) {
                $binaryString .= chr(bindec($byte));
            }
        }

        return $binaryString;
    }

    /**
     * Convert an integer to a bytestring
     *
     * @param int $int The integer to convert
     * @return string The bytestring representation of the integer
     */
    public function intToBytestring($int)
    {
        $bytes = pack('N', $int); // Pack integer into a 32-bit binary string
        return str_pad($bytes, 8, "\000", STR_PAD_LEFT); // Pad with null bytes to ensure 8-byte length
    }
}
