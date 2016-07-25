<?php
/**
 * Antworten von Paydirekt parsen,
 * wenn sinnvoll.
 * 
 * @autor Michael Töpfer
 * @license MIT
 */
namespace Paydirekt\Client\Utility;

use Paydirekt\Client\Utility\utils;

class responseMessages {
    
	/**
	 * Parsed die Rückantworten von payDirekt
	 * @param enum $gvType
	 * @param mixed $message
	 * @return mixed
	 */
    protected function parse($gvType, $message){		
        if(utils::is_json($message)){
        	$message = json_decode($message,true);
        }
		switch (strtoupper($gvType)) {
			case 'DIRECT_SALE':
				$message = self::parseDirectSale($message);
				break;
		}
		return $message;
    }
    
	/**
	 * Antwort aus einem ORDER/DIRECT_SALE
     * 
	 * @param array
	 * @return array
	 */
    private function parseDirectSale ($message){     
        $ret = array(
            'checkoutId'    => '',
            'href'          => ''
        );
        $ret['checkoutId'] = isset($message['checkoutId']) ? $message['checkoutId'] : '';
        $ret['href'] = isset($message['_links']) ? $message['_links']['approve']['href'] : '';
		return $ret;
    }
      
}

?>