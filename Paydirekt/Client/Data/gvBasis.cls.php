<?php
/**
 * Basis Elternklasse Geschäftsvorfall.
 * Muss von allen Geschäftsvorfallklassen (gv*.cls.php)
 * extended werden.
 * 
 * @autor Michael Töpfer
 * @license MIT
*/

namespace Paydirekt\Client\Data;

require_once 'interfaceGv.cls.php';
 
class gvBasis {
	
	private $url;
	private $header;
    protected $Hash;
	
	function __construct() {
		$this->url 		= '';
		$this->header 	= array();
        $this->Hash     = self::getHash();
	}
	final public function setURL ($url){
		$this->url = $url;
	}
	final public function getURL() {
		return $this->url;
	}
	final public function getHeaderVars() {
		$ret = array();
		foreach ($this->header as $key => $value) {
			$ret[] = $value;
		}
		return $ret;
	}
    final public function removeHeaderItem ($key){		
		 unset($this->header[$key]);
    }
    final public function addHeaderItem ($key , $value){		
		 $this->header[$key] = "$key: $value";
    }
    final public function getHash() {
        if(!$this->Hash){
            $this->Hash = md5(uniqid('', true));
        }
        return $this->Hash;
    }
}
?>