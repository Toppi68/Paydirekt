<?php
 /**
 * Checkout
 * Fragt einen Checkout über die checkoutId ab.
 * 
 * @autor Michael Töpfer
 * @license MIT
 */
namespace Paydirekt\Client\Data;

use \Paydirekt\Client\Constants;

class gvCheckout extends gvBasis implements interfaceGv {
    
    const Type = 'CHECKOUT';
    
    function __construct() {
        //Header
        parent::addHeaderItem('Authorization','Bearer');
        parent::addHeaderItem('Content-Type','text/html;charset=utf-8');
        parent::addHeaderItem('Accept','application/hal+json');
    }
    public function toArray() {
        return array();
    }
    public function setCheckoutId ($checkoutId){
        parent::setUrl(Constants::URL.Constants::URL_CHECKOUT.'/'.substr($checkoutId,0,100));        
    }  
}
?>