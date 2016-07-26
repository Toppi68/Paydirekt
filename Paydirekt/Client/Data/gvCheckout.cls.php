<?php
 /**
 * Checkout
 * Fragt einen Checkout über die checkoutId ab.
 * 
 * @autor Michael Töpfer
 * @license MIT
 */

namespace Paydirekt\Client\Data;

use Paydirekt\Client\Constants;
use Paydirekt\Client\Data\gvBasis;
use Paydirekt\Client\Data\interfaceGv;

class gvCheckout extends gvBasis implements interfaceGv {
    
    const Type = 'CHECKOUT';
    
    private $CheckoutId;
    
    function __construct() {
        //Header
        parent::addHeaderItem('Authorization','Bearer');
        parent::addHeaderItem('Content-Type','text/html;charset=utf-8');
        parent::addHeaderItem('Accept','application/hal+json');
    }
    public function setCheckoutId ($checkoutId){
        $this->CheckoutId = $checkoutId;
        parent::setUrl(Constants::URL.Constants::URL_CHECKOUT.'/'.substr($checkoutId,0,100));        
    }
    public function toArray() {
        return array();
    }
    /**
     * Sendet diesen GV an Paydirekt
     * @param void
     * @return array
     */
    public function send() {
        return parent::sendGV($this);
		session::unsetCheckout($this->CheckoutId); 
    } 
}
?>