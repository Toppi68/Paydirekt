<?  
/**
 * Projekt: Paydirekt
 * 
 * Paydirekt-Rücksprung auswerten
 * 
 * @autor Michael Töpfer
 * @license MIT
 * 
 */
use \Paydirekt\Client\payDirekt;
use \Paydirekt\Client\Utility\utils;

require_once 'Paydirekt/Client/payDirekt.cls.php';

$ok     = false;
$hash   = utils::getParam('hash','');
$action = utils::getParam('action','');
if(!$hash){
    $action = '';
}
switch ($action) {
    case 'reject':
    case 'cancel':
        //Abbruch
        $response_text ='Der Zahlvorgang wurde abgebrochen.';
        break;
    case 'age':
        //Altersverfikation fehlgeschlagen
        $response_text ='Du musst zumindest 18 Jahre alt sein.';
        break;        
    case 'ok':
        //Hat geklappt
        $ok = true;
        break;
    default:
        //Sonsitges Problem
        $response_text ='Ungültiger Aufruf';
        
}
if(!$ok){
    echo $response_text;
    exit;
}

//Paydirekt starten
$clsPD  = new payDirekt();

//Lokalen Vorgang ziehen
$req = $clsPD->getOrderValuesByHash($hash);
if(!$req['checkoutId']){
    echo "Die Seite ist nicht mehr gültig.";
    exit;
}

//Zahlungsgarantie bei Paydirekt abfragen.
$checkout = $clsPD->getGV('CHECKOUT');
$checkout->setCheckoutId($req['checkoutId']);
$req = $checkout->send();

if(!$req['success'] || $req['response']['status'] != 'APPROVED'){
    echo "Zahlung konnte nicht verifiziert werden";
    exit;
}

echo "<pre>";print_r($req['response']);

/*
 * Zahlung verbuchen
 * 
 * Dein Code...
 * 
 * 
 */
?>
