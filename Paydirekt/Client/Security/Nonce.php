<?php

namespace Paydirekt\Client\Security;

use Paydirekt\Client\Utility\Base64Url;

/**
 * A nonce is a random arbitrary character sequence that may only be used once.
 */
final class Nonce
{
    /**
     * Private constructor.
     * <p>
     * This class provides static functions only.
     */
    private function __construct() {}

    /**
     * Creates a random nonce as a character sequence of length 64.
     *
     * @return string The nonce.
     */
    public static function createRandomNonce()
    {
        $bytes = Random::createRandomPseudoBytes(48);
        return Base64Url::encode($bytes);
    }
}
