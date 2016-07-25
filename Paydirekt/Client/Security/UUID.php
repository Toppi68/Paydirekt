<?php

namespace Paydirekt\Client\Security;

/**
 * The UUID is an identifier standard, representing a 128-bit value.
 */
final class UUID
{
	/**
     * Private constructor.
     * <p>
     * This class provides static functions only.
     */
    private function __construct() {}

    /**
     * Creates a random UUID according to RFC 4122 - Section 4.4.
     *
     * @return string The UUID.
     */
    public static function createRandomUUID()
	{
        $data = Random::createRandomPseudoBytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

	/**
     * Checks whether the specified string is a valid UUID.
	 *
     * @param string $string The string to check.
     *
     * @return bool True, when given string is a valid UUID, otherwise false.
     */
    public static function isUUID($string)
    {
        return preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/", $string) ? true : false;
    }
}
