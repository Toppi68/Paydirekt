<?php
/**
 * Basisklasse Paydirekt
 * 
 * @autor Michael Töpfer
 * @license MIT
 */

namespace Paydirekt\Client;

use Paydirekt\Client\Constants;
use Paydirekt\Client\Data\address;
use Paydirekt\Client\Data\item;
use Paydirekt\Client\Data\gvOrder;
use Paydirekt\Client\Data\gvCheckout;
use Paydirekt\Client\Utility\com;
use Paydirekt\Client\Utility\utils;
use Paydirekt\Client\Utility\session;

require_once 'Paydirekt/Client/constants.php';
require_once 'Paydirekt/Client/Utility/com.cls.php'; 
require_once 'Paydirekt/Client/Utility/utils.cls.php';
require_once 'Paydirekt/Client/Utility/session.cls.php';

class payDirekt extends com {

	public function __construct() {
        session::start();
	}
    
    /**
     * Gibt ein Paydirekt-Objekt-Geschäftsvorfall 
     * oder false zurück.
     * 
     * @param enum [DIRECT_SALE,CHECKOUT]
     * @return object oder false
     */
    public function getGV ($gvType='DIRECT_SALE') {        
        $gvType = strtoupper($gvType);
        switch ($gvType) {
            case 'DIRECT_SALE':
                require_once 'Data/gvOrder.cls.php';
                return new gvOrder();
                break;
            case 'CHECKOUT':
                require_once 'Data/gvCheckout.cls.php';
                return new gvCheckout();               
                break;
            
            default:
                return false;
        }
    }
    
    /**
     * Gibt ein Objekt Adresse zurück.
     * 
     * @param void
     * @return object
     */
	public function getAddress (){
        require_once 'Data/address.cls.php';
        return new address();	
	}
    /**
     * Gibt ein Objekt Item zurück.
     * 
     * @param void
     * @return object
     */    
	public function getItem(){
	    require_once 'Data/item.cls.php';
	    return new item();
	}
	
    /**
     * Sendet ein Objekt-Geschäftsvorfall
     * (aus self::getGV()) an Paydirekt.
     * 
     * @param object Geschäftsvorfall
     * @return array
     */
    public function send($objGV) {
        return parent::sendGV($objGV);
    }
    /**
     * Fragt ein Checkout bei Paydirekt anhand
     * der CheckoutId ab.
     * 
     * @param string CheckoutId
     * @return array Checkout
     */
    public function getCheckout ($checkoutId){
        $objGV = self::getGV('CHECKOUT');
        $objGV->setCheckoutId($checkoutId);
        return parent::sendGV($objGV);
    }
    /**
     * Schließt einen Vorgang durch das
     * löschen der in der Session gespeicherten
     * Daten.
     * 
     * @param string $checkoutId
     * @return void
     */
    public function closeCheckout($checkoutId){
        session::unsetCheckout($checkoutId);   
    }
    /**
     * Gibt die in der PHP-Session zu diesem
     * Hash gespeicherten Werte wieder: 
     * Wird in der Regel nach dem Rücksprung aus Paydirekt
     * verwendet.
     * 
     * Rückgabewerte:
     * Array(
     *      merchantCustomerNumber, merchantOrderReferenceNumber, 
     *      merchantInvoiceReferenceNumber, checkoutId
     * )
     * 
     * @param string 32Hash
     * @return array
     */
    public function getOrderValuesByHash($hash){
        $arr = session::getValuesByHash($hash);
        return array(
            'merchantCustomerNumber'            => isset($arr['merchantCustomerNumber']) ? $arr['merchantCustomerNumber'] : 0,
            'merchantOrderReferenceNumber'      => isset($arr['merchantOrderReferenceNumber']) ? $arr['merchantOrderReferenceNumber'] : 0,
            'merchantInvoiceReferenceNumber'    => isset($arr['merchantInvoiceReferenceNumber']) ? $arr['merchantInvoiceReferenceNumber'] : 0,
            'checkoutId'                        => isset($arr['checkoutId']) ? $arr['checkoutId'] : 0,
        );
        return $arr;
    }
}
?>