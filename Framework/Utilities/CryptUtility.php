<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 14.05.15
 * Time: 14:24
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

/**
 * Class CryptUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class CryptUtility
{
    /**
     * Encrypt Value by Key
     *
     * @param string $sValue
     * @param string $sSecretKey
     *
     * @return string
     */
    public static function encrypt($sValue, $sSecretKey)
    {
        if (empty($sValue)) {
            return "";
        }

        $sSecretKey = substr($sSecretKey, 0, 30);

        return rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, $sValue, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))), "\0");
    }

    /**
     * Decrypt value by key
     *
     * @param string $sValue
     * @param string $sSecretKey
     *
     * @return string
     */
    public static function decrypt($sValue, $sSecretKey)
    {
        if (empty($sValue)) {
            return "";
        }

        $sSecretKey = substr($sSecretKey, 0, 30);

        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, base64_decode($sValue), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)), "\0");
    }
}