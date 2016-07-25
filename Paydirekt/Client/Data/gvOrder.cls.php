<?php
 /**
 * Checkout ORDER oder DIRECT_SALE
 * Datentyp für einen Checkout.
 * 
 * @autor Michael Töpfer
 * @license MIT
 */
namespace Paydirekt\Client\Data;

use Paydirekt\Client\Data\address;
use Paydirekt\Client\Data\gvBasis;
use Paydirekt\Client\Data\item;
use Paydirekt\Client\Constants;
use Paydirekt\Client\Utility\utils;

require_once 'gvBasis.cls.php';
require_once 'address.cls.php';
require_once 'item.cls.php';

/**
 * 
 */
class gvOrder extends gvBasis implements interfaceGv{
	
	const Type = 'DIRECT_SALE';
    	
	public $Address;
    protected $Order;
    
	function __construct() {
		        
		$this->Address = new address();
		
		/*Pflichtfelder*/
		$this->Order = array(
			'type'										=> self::Type,
			'totalAmount'								=> 0,
			'shippingAmount'							=> 0,
			'orderAmount'								=> 0,
			'currency'									=> 'EUR',
			'shippingAddress'							=> $this->Address->toArray(),
			'merchantOrderReferenceNumber'				=> '',
			'redirectUrlAfterSuccess'					=> Constants::URL_RESPONSE,
			'redirectUrlAfterCancellation'				=> Constants::URL_RESPONSE,
			'redirectUrlAfterRejection'					=> Constants::URL_RESPONSE
		);
				
		parent::setUrl(Constants::URL.Constants::URL_CHECKOUT);
		parent::addHeaderItem('Authorization','Bearer');
		parent::addHeaderItem('Content-Type','application/hal+json;charset=utf-8');
		parent::addHeaderItem('Accept','application/hal+json');		

	}
    
    public function toArray() {
        
        $this->Order['shippingAddress'] = $this->Address->toArray();
        return $this->Order;
    }
    
	/**
	 * <type> String Pflichtfeld.
	 * 
	 * Die Art der Bestellung: DIRECT_SALE oder ORDER. Im Falle eines DIRECT_SALE
	 * (Direktbestellung mit Einmalzahlung) wird automatisch eine Zahlungsautorisierung erzeugt.
	 * Der Händler erhält sofort eine Zahlungsgarantie.
	 * Zu einer ORDER (Vorbestellung oder Bestellung mit Teilzahlungen) können direkt im Anschluss
	 * oder später ein oder mehrere Captures angestoßen werden. Für eine Order wird keine
	 * Zahlungsgarantie an den Händler ausgesprochen. Die Garantie wird erst mit dem Capture 
	 * gegeben.
	 * 
	 * @param string $type
	 * @return void
	*/
	public function setType ($type='DIRECT_SALE'){
		$type = strtoupper($type);
		switch ($type) {
			case 'ORDER':
			case 'DIRECT_SALE':	
				break;
			default:
				return;
		}
		$this->Order['type'] = $type;
	}
	
	/**
	 * <totalAmount> Number Pflichtfeld.
	 * Zwischen 0.01 und 50000.
	 * Maximal zwei Nachkommastellen.
	 * Der Gesamtbetrag der Bestellung, inkl. aller Lieferkosten. Es werden maximal 
	 * 2 Nachkommastellen unterstützt. Im Falle eines DIRECT_SALE wird eine Zahlung für 
	 * diesen Betrag initiiert. Im Falle einer ORDER können Captures bis maximal zu diesem 
	 * Betrag angestoßen werden.
	 * 
	 * @param float $amount
	 * @return void
	 */
	public function setTotalAmount ($amount){
		if(!is_numeric($amount) || $amount < 0 || $amount > 50000){
			return;
		}
		$this->Order['totalAmount'] = $amount;		
	}
	
	/**
	 * <shippingAmount> Number Pflichtfeld.
	 * Mindestens 0.00.
	 * Maximal zwei Nachkommastellen.
	 * Die Versandkosten der Bestellung. Dieser Wert wird nicht für Berechnungen verwendet,
	 * sondern dient nur zur Information.
	 * 
	 * @param float $amount
	 * @return void
	 */
	public function setShippingAmount($amount){
		if(!is_numeric($amount) || $amount < 0 || $amount > 50000){
			return;
		}
		$this->Order['shippingAmount'] = $amount;
	}
	/**
	 * <orderAmount> Number Pflichtfeld.
	 * Zwischen 0.01 und 50000.
	 * Maximal zwei Nachkommastellen.
	 * Der Warenwert der Bestellung, ohne Versandkosten. Dieser Wert wird nicht für Berechnungen
	 * verwendet, sondern dient nur zur Information.
	 * 
	 * @param float $amount
	 * @return void
	 */
	public function setOrderAmount($amount){
		if(!is_numeric($amount) || $amount < 0 || $amount > 50000){
			return;
		}
		$this->Order['orderAmount'] = $amount;
	}
	
	/**
	 * <items> Array Optional.
	 * Array von Items.
	 * Die einzelnen Positionen des Warenkorbs.
	 * Es wird empfohlen, diese Werte zu übergeben. Dies verbessert die Erkennung von fraudulenten
	 * Vorgängen und hilft, Disputes zu vermeiden.
	 * 
	 * Kommt aus einem Objekt item->toArray()
	 * 
	 * @param string $item
	 */
	public function addItem($objItem){
		$this->Order['items'][] = $objItem->toArray();
	}
	
	/**
	 * <shippingAddress> Object Pflichtfeld.
	 * Die Lieferanschrift des Empfängers. Diese Adresse wird dem Kunden nach dem Login im
	 * paydirekt-System zur Kontrolle angezeigt.
	 * 
	 * Kommt aus einem Objekt address->toArray()
	 * 
	 * @param array $arrAdr
	 */
	public function setShippingAddress($objAdress){
        $this->Address->setOpts($objAdress->toArray());
	}
     
  	/**
	 * <merchantCustomerNumber> String Optional.
	 * Maximal 20 Zeichen.
	 * Händler-interne Kundennummer des Käufers. Wird dem Kunden in der Transaktionsübersicht 
	 * angezeigt. Wird dem Händler in der Transaktionsübersicht angezeigt.
	 * @param string $value
	 */  
	public function setMerchantCustomerNumber($value){
		$this->Order['merchantCustomerNumber'] = substr($value,0,20);	
	}
    
    /**
	 * <merchantOrderReferenceNumber> String Pflichtfeld.
	 * Maximal 20 Zeichen. Nur SEPA-konforme Zeichen.
	 * Händler-interne, eindeutige Bestellnummer für diesen Kaufvorgang.
	 * Wird dem Kunden in der Transaktionsübersicht angezeigt. Wird dem Händler in der
	 * Transaktionsübersicht angezeigt. Die Bestellnummer wird in der Händler-Lastschrift als 
	 * instructionId und im Verwendungszweck bei Händler und Käufer verwendet.
	 * 
	 * @param string $value
	 */
    public function setMerchantOrderReferenceNumber($value){
        $this->Order['merchantOrderReferenceNumber'] = substr(utils::getValidSepaString($value),0,20);
    }
	
	/**
	 * <merchantInvoiceReferenceNumber> String Optional.
	 * Maximal 20 Zeichen.
	 * Händler-interne, eindeutige Rechnungsnummer für diesen Kauf- bzw. Zahlvorgang.
	 * Wird dem Händler in der Transaktionsübersicht angezeigt.
	 * 
	 */
    public function setMerchantInvoiceReferenceNumber($value){
        $this->Order['merchantInvoiceReferenceNumber'] = substr($value,0,20);
    }	

    /**
     * <sha256hashedEmailAddress> String Optional.
     * Maximal 64 Zeichen.
     * Die E-Mail Adresse des Käufers als Base-64 encodierter SHA-256 Hash-Wert ohne Padding,
     * sofern vorhanden. Pseudo-Code: sha256hashedEmailAddress = toStringUTF8(base64(sha256(toBytesUTF8(emailAddress)))).
     * Beispiel: Aus der E-Mail-Adresse max@muster.de muss sich exakt der folgende HashWert ergeben:
     * 6JL4VUgVxkq2m+a9I6ScfW2ofJP5y6wsvSaHIsX+iLs
     * Diese Information wird zur Fraud Prevention verwendet.
     * 
     * @param string $email
     */
    public function setSHA256hashedEmailAddress($email){
        $this->Order['sha256hashedEmailAddress'] = base64_encode( hash('sha256', utf8_encode($email),true));
    }
	
	/**
	 * <redirectUrlAfterSuccess> String Pflichtfeld.
	 * Maximal 2000 Zeichen.
	 * Die Rücksprung-Adresse des Webshops (inkl. Referenz auf die Bestellung), 
	 * die nach erfolgreicher Bezahlung aufgerufen wird.
	 * 
     * @param string $queryString
	 */
    public function setUrlParamAfterSuccess($queryString=''){  
        $this->Order['redirectUrlAfterSuccess'] = substr(Constants::URL_RESPONSE.$queryString, 0,2000);        
    }
	
	/**
	 * <redirectUrlAfterCancellation> String Pflichtfeld.
	 * Maximal 2000 Zeichen.
	 * Die Rücksprung-Adresse des Webshops (inkl. Referenz auf die Bestellung), 
	 * die im Falle eines Abbruchs oder technischen Fehlers aufgerufen wird. 
	 * Damit wird signalisiert, dass der Kaufvorgang grundsätzlich fortgeführt werden kann 
	 * und im Anschluss ein weiterer, neuer Checkout initiiert werden kann, 
	 * beispielsweise, weil der Kunde noch einen Artikel in der Bestellung hinzufügen möchte.
	 * 
     * @param string $queryString
	 */
    public function setUrlParamAfterCancellation($queryString=''){
        $this->Order['redirectUrlAfterCancellation'] = substr(Constants::URL_RESPONSE.$queryString, 0,2000);        
    }
	
	/**
	 * <redirectUrlAfterRejection> String Pflichtfeld.
	 * Maximal 2000 Zeichen.
	 * Die Rücksprung-Adresse des Webshops (inkl. Referenz auf die Bestellung),
	 * die im Falle einer Abweisung der Zahlung aufgerufen wird. Damit wird signalisiert,
	 * dass keine Zahlung autorisiert wurde (z. B. aufgrund falscher TAN-Eingabe,
	 * fehlender Bank-Autorisierung oder Betrugsverdacht). Falls das Webshop-System keine
	 * Unterscheidung zwischen redirectUrlAfterCancellation und redirectUrlAfterRejection 
	 * unterstützt,	kann in beiden Feldern die gleiche URL angegeben werden.
	 * 
     * @param string $queryString
	 */
    public function setUrlParamAfterRejection($queryString=''){        
        $this->Order['setRedirectUrlAfterRejection'] = substr(Constants::URL_RESPONSE.$queryString, 0,2000);        
    }
	
	/**
	 * <minimumAge> Number Optional.
	 * Das Mindestalter (in Jahren), das der Käufer erreicht haben muss, um die Bestellung 
	 * ausführen zu dürfen,	beispielsweise, weil sie Artikel enthält, die einer Altersbeschränkung 
	 * unterliegen (Filme, Computerspiele). Die Prüfung erfolgt direkt nach dem Login des Käufers
	 * in das paydirekt-System. Bei erfolgreicher Verifikation wird der Ablauf ohne weitere 
	 * Meldung wie gewohnt fortgesetzt. Bei nicht-erfolgreicher Verifikation, d. h. der Kunde 
	 * hat das erforderliche Mindestalter noch nicht erreicht, erfolgt eine Umleitung auf den 
	 * Link redirectUrlAfterAgeVerificationFailure (siehe unten). 
	 * Bei Nicht-Angabe dieses Wertes erfolgt keine Altersverifkation.
	 * 
	 * @param int $age
	 */
    public function setMinimumAge($age){
        $this->Order['minimumAge'] = is_numeric($age) && $age >0 && $age < 150 ? $age : 0;
    }
	
	/**
	 * <redirectUrlAfterAgeVerificationFailure> String Pflichtfeld, wenn minimumAge gesetzt ist.
	 * Maximal 2000 Zeichen.
	 * Die Rücksprung-Adresse des Webshops (inkl. Referenz auf die Bestellung), 
	 * die im Falle einer nicht erfolgreichen Altersverifikation aufgerufen wird.
	 * Damit wird signalisiert, dass die Bestellung abgebrochen wurde, da der Käufer das
	 * erforderliche Mindestalter für mindestens einen Artikel in der Bestellung noch nicht 
	 * erreicht	hat. Dieses Feld wird im Falle einer geforderten Altersverifikation, 
	 * d. h. bei Angabe des Feldes minimumAge, zum Pflichtfeld. 
	 * Ist keine Altersverifikation erforderlich, ist dieses Feld wegzulassen.
	 * 
     * @param string $queryString
	 */
    public function setUrlParamAfterAgeVerificationFailure($queryString=''){
        $this->Order['redirectUrlAfterAgeVerificationFailure'] = substr(Constants::URL_RESPONSE.$queryString, 0,2000); 
    }
	
	/**
	 * <note> String Optional.
	 * Maximal 37 Zeichen.
	 * Freitextfeld, das auf dem Kontoauszug im Feld Verwendungzweck erscheint (nur DIRECT_SALE).
     * 
     * Wird kein Text übergeben, wird der Std aus Constants::ORDER_NOTE verwendet.
     * 
     * @param string $text
	 */
    public function setNote($text=''){
        if($this->Order['type'] == 'DIRECT_SALE'){
            $text = $text ? $text : Constants::ORDER_NOTE;
            $this->Order['note'] = substr($text,0,37);   
        }         
    }
}


?>

