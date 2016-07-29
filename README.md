# Paydirekt
Paydirekt PHP implementation basierend auf paydirekt-php von David Orlea 
https://github.com/paydirekt/paydirekt-php

## Aktuell unterstüzte Geschäftsvorfalle
* Order DIRECT_SALE, Request Checkout.

## QUICKSTART
* /Paydirekt/constants.php anpassen.
* beispiel_request.php aufrufen.

## Anforderungen
* PHP 5.5 oder höher
* mcrypt_create_iv() oder openssl_random_pseudo_bytes() oder random_bytes() 
* Curl()

## Empfehlung
* Wenn PHP 5.x, https://github.com/paragonie/random_compat für random_bytes() installieren.
* Ab PHP 7.0 enthalten 

## License
* MIT
*

## See also
https://toppi68.github.io/Paydirekt/
