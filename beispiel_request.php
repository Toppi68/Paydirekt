<?  
/**
 * Beispiel DirektSale
 * 
 * Sendet einen Direkt_Sale an Paydirekt.
 * Authorisation, wie in der Klasse Constants gesetzt.
 * 
 * Bei Erfolg, wird Paydirekt die Datei 
 * beispiel_response.php aufrufen.
 * 
 * @autor Michael Töpfer
 * @license MIT
 */
use \Paydirekt\Client\payDirekt;

require_once 'Paydirekt/Client/payDirekt.cls.php';

//Basisklasse starten
$clsPD = new payDirekt();

//Einen Directsale abwicklen.
$order = $clsPD->getGV('DIRECT_SALE');

//Bestelldaten setzen
$order->setOrderAmount(12.50);
$order->setShippingAmount(3);
$order->setTotalAmount(15.50);
$order->setMerchantCustomerNumber(123);
$order->setMerchantOrderReferenceNumber(456);
$order->setMerchantInvoiceReferenceNumber(789);
$order->setSHA256hashedEmailAddress('max@muster.de');
$order->setMinimumAge(18);

//Hash-Secret für diesen Vorgang
$hash = $order->getHash();

//Eindeutige Rücksprungadressen mit HashSecret setzten
$order->setUrlParamAfterSuccess("?action=ok&hash=$hash");
$order->setUrlParamAfterRejection("?action=reject&hash=$hash");
$order->setUrlParamAfterCancellation("?action=cancel&hash=$hash");
$order->setUrlParamAfterAgeVerificationFailure("?action=age&hash=$hash");

//Std-Auszugstext setzen (laut Constants)
$order->setNote();

//Lieferadresse setzten
$order->Address->setGivenName('Max');
$order->Address->setLastName('Muster');
$order->Address->setZip(28195);
$order->Address->setCity('Bremen');
$order->Address->setStreet('Am Brill');
$order->Address->setStreetNr('1-3');
if($re['adresse']['firmenname']){
    $order->Address->setCompany('Sparkasse Bremen AG');
}

//Rechnungposten zur Info als Item übergeben
$item = $clsPD->getItem();
$item->setQuantity(1);
$item->setName('Sparschwein');
$item->setPrice(12.50);
$order->addItem($item);
    
//Order abschicken
$req = $clsPD->send($order);

if($req['success']){
    header("HTTP/1.1 302 Found");
    header("Location: ".$req['response']['href']);
    header("Connection: close");
} else {
    echo "<pre>Fehler: ".print_r($req);
}
exit;


?>