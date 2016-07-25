<?php

namespace Paydirekt\Client\Utility;

/**
 * Encoders and decoders for the Base64Url encoding scheme.
 */
final class Base64Url
{
    /**
     * Private constructor.
     * <p>
     * This class provides static functions only.
     */
    private function __construct() {}

    /**
     * Encodes data with Base64Url.
     *
     * @param string $string The string to encode in Base64Url.
     *
     * @return string The Base64Url encoding of the given string.
     */
    public static function encode($string)
    {
        return strtr(base64_encode($string), '+/', '-_');
    }

    /**
     * Decodes Base64Url encoded data.
     *
     * @param string $string The Base64Url encoded string to decode.
     *
     * @return string The Base64Url decoding of the given string.
     */
    public static function decode($string)
    {
        return base64_decode(str_pad(strtr($string, '-_', '+/'), strlen($string) % 4, '=', STR_PAD_RIGHT));
    }

    /**
     * Checks whether the specified string is a valid Base64Url encoding.
     *
     * @param string $string The string to check.
     *
     * @return bool True, when given string is a valid Base64Url encoding, otherwise false.
     */
    public static function isBase64UrlEncoded($string)
    {
        return preg_match("/^[0-9a-zA-Z-_]+[=]{0,2}$/", $string) ? true : false;
    }
}
