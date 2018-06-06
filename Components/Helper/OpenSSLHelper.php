<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 19.04.2018
 * Time: 09:47
 */

namespace PM\Bundle\ToolBundle\Components\Helper;


/**
 * Class OpenSSLHelper
 *
 * @package PM\Bundle\ToolBundle\Components\Helper
 */
class OpenSSLHelper
{
    const CIPHER_AES_256_CBC = 'AES-256-CBC';

    /**
     * Encrypt
     *
     * @param string $plainText
     * @param string $key
     * @param string $cipher
     *
     * @return string
     */
    public static function encrypt($plainText, $key, $cipher = self::CIPHER_AES_256_CBC)
    {
        if (false === in_array($cipher, openssl_get_cipher_methods())) {
            throw new \LogicException(sprintf('Cipher %s not supported', $cipher));
        }

        $ivLen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivLen);

        $cipherTextRaw = openssl_encrypt($plainText, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $cipherTextRaw, $key, true);

        return base64_encode(sprintf('%s%s%s', $iv, $hmac, $cipherTextRaw));
    }

    /**
     * Decrypt
     *
     * @param string $encryptedText
     * @param string $key
     * @param string $cipher
     *
     * @return string
     */
    public static function decrypt($encryptedText, $key, $cipher = self::CIPHER_AES_256_CBC)
    {
        if (false === in_array($cipher, openssl_get_cipher_methods())) {
            throw new \LogicException(sprintf('Cipher %s not supported', $cipher));
        }

        $encryptedText = base64_decode($encryptedText);

        $ivLen = openssl_cipher_iv_length($cipher);
        $iv = substr($encryptedText, 0, $ivLen);

        $hmac = substr($encryptedText, $ivLen, 32);
        $cipherTextRaw = substr($encryptedText, $ivLen + 32);

        $plainText = openssl_decrypt($cipherTextRaw, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        $calculatedHmac = hash_hmac('sha256', $cipherTextRaw, $key, true);

        if (false === hash_equals($hmac, $calculatedHmac)) {
            throw new \LogicException('Decrypt failed.');
        }

        return $plainText;
    }
}