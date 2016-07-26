<?php
/**
 * Basisklasse Paydirekt
 * 
 * @autor Michael Töpfer
 * @license MIT
 */
namespace Paydirekt\Client;

define ('_INCLUDEPATH' , dirname(__FILE__));

error_reporting(E_ALL);

use Paydirekt\Client\Constants;
use Paydirekt\Client\Data\address;
use Paydirekt\Client\Data\item;
use Paydirekt\Client\Data\gvOrder;
use Paydirekt\Client\Data\gvCheckout;
use Paydirekt\Client\Utility\utils;
use Paydirekt\Client\Utility\session;

require_once (_INCLUDEPATH.'/constants.php');
require_once (_INCLUDEPATH.'/Utility/utils.cls.php');
require_once (_INCLUDEPATH.'/Utility/session.cls.php');
require_once (_INCLUDEPATH.'/Data/gvBasis.cls.php');
require_once (_INCLUDEPATH.'/Data/interfaceGv.cls.php');

class payDirekt {

	public function __construct() {
        session::start();
	}
    /**
	 * Geschäftsvorfall-Factory
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
				require_once (_INCLUDEPATH.'/Data/gvOrder.cls.php');         
                return new gvOrder();
                break;
            case 'CHECKOUT':
				require_once (_INCLUDEPATH.'/Data/gvCheckout.cls.php');
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
		require_once (_INCLUDEPATH.'/Data/address.cls.php');
        return new address();	
	}
    /**
     * Gibt ein Objekt Item zurück.
     * 
     * @param void
     * @return object
     */    
	public function getItem(){
		require_once (_INCLUDEPATH.'/Data/item.cls.php');
	    return new item();
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