# Paydirekt
Paydirekt PHP implementation

## Aktuell unterstüzte Geschäftsvorfalle
* Order DIRECT_SALE, Request Checkout.

## QUICKSTART
* Einfach die Datei beispiel_request.php starten.
* Wer bereits über ein eigenes Händlersecret verfügt, kann diese Einstellungen in der Datei /Paydirekt/constants.php vornehmen.

## Anforderungen
* PHP 5.5 oder höher
* mcrypt_create_iv() oder openssl_random_pseudo_bytes() oder random_bytes() 
* Curl()

## Empfehlung
* Wenn PHP 5.x, https://github.com/paragonie/random_compat für random_bytes() installieren.
* Ab PHP 7.0 enthalten 

## License
* MIT
