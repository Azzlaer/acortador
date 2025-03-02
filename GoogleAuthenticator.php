<?php
/**
 * PHPGangsta Google Authenticator
 * https://github.com/PHPGangsta/GoogleAuthenticator
 */

class PHPGangsta_GoogleAuthenticator
{
    protected $_codeLength = 6;

    /**
     * Create a new secret
     */
    public function createSecret($secretLength = 16)
    {
        $validChars = $this->_getBase32LookupTable();
        $secret = '';

        for ($i = 0; $i < $secretLength; $i++) {
            $secret .= $validChars[array_rand($validChars)];
        }

        return $secret;
    }

    /**
     * Calculate the code for the provided secret and point in time
     */
    public function getCode($secret, $timeSlice = null)
    {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }

        $secretKey = $this->_base32Decode($secret);
        $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
        $hash = hash_hmac('sha1', $time, $secretKey, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $truncatedHash = substr($hash, $offset, 4);
        $value = unpack('N', $truncatedHash)[1] & 0x7FFFFFFF;

        return str_pad($value % pow(10, $this->_codeLength), $this->_codeLength, '0', STR_PAD_LEFT);
    }

    /**
     * Get the URL of the QR code for the Google Authenticator app
     */
    public function getQRCodeGoogleUrl($name, $secret)
    {
        return sprintf(
            "https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=otpauth://totp/%s?secret=%s",
            urlencode($name),
            $secret
        );
    }

    /**
     * Verify the code
     */
    public function verifyCode($secret, $code, $discrepancy = 1, $currentTimeSlice = null)
    {
        if ($currentTimeSlice === null) {
            $currentTimeSlice = floor(time() / 30);
        }

        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $calculatedCode = $this->getCode($secret, $currentTimeSlice + $i);
            if ($calculatedCode === $code) {
                return true;
            }
        }

        return false;
    }

    /**
     * Helper to decode base32
     */
    protected function _base32Decode($secret)
    {
        if (empty($secret)) {
            return '';
        }

        $base32Chars = $this->_getBase32LookupTable();
        $base32CharsFlipped = array_flip($base32Chars);
        $paddingCharCount = substr_count($secret, '=');
        $allowedValues = [6, 4, 3, 1, 0];

        if (!in_array($paddingCharCount, $allowedValues)) {
            return false;
        }

        for ($i = 0; $i < 4; $i++) {
            if ($paddingCharCount == $allowedValues[$i] &&
                substr($secret, -($allowedValues[$i])) != str_repeat('=', $allowedValues[$i])) {
                return false;
            }
        }

        $secret = str_replace('=', '', $secret);
        $binaryString = '';

        for ($i = 0; $i < strlen($secret); $i += 8) {
            $x = '';

            if (!in_array($secret[$i], $base32Chars)) {
                return false;
            }

            for ($j = 0; $j < 8; $j++) {
                $x .= str_pad(base_convert(@$base32CharsFlipped[$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
            }

            $eightBits = str_split($x, 8);
            foreach ($eightBits as $eightBit) {
                $binaryString .= chr(base_convert($eightBit, 2, 10));
            }
        }

        return $binaryString;
    }

    /**
     * Get the Base32 lookup table
     */
    protected function _getBase32LookupTable()
    {
        return [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', // 0-7
            'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 8-15
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 16-23
            'Y', 'Z', '2', '3', '4', '5', '6', '7', // 24-31
            '=',  // Padding character
        ];
    }
}
