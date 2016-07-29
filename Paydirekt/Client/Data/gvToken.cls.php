<?php
 /**
 * Fragt einen Token für die Auth. 
 * bei Übertragungen an Paydirekt an.
 * 
 * Basierend auf paydirekt-php von David Orlea
 * https://github.com/paydirekt/paydirekt-php
 * 
 * @autor Michael Töpfer
 * @license MIT
 */

namespace Paydirekt\Client\Data;

use \Paydirekt\Client\Constants;
use \Paydirekt\Client\Data\gvBasis;
use \Paydirekt\Client\Data\interfaceGv;

use \Paydirekt\Client\Security\Random;
use \Paydirekt\Client\Security\Hmac;
use \Paydirekt\Client\Security\Nonce;
use \Paydirekt\Client\Security\UUID;

use \Paydirekt\Client\Utility\Base64Url;

require_once (_INCLUDEPATH.'/Security/Hmac.php');
require_once (_INCLUDEPATH.'/Security/Nonce.php');
require_once (_INCLUDEPATH.'/Security/UUID.php');
require_once (_INCLUDEPATH.'/Security/Random.php');
require_once (_INCLUDEPATH.'/Utility/Base64Url.php');

class gvToken extends gvBasis implements interfaceGv {
	
	const Type = 'TOKEN';
	
    private $Token;
	
	function __construct() {

        $requestId 		= UUID::createRandomUUID();
        $randomNonce 	= Nonce::createRandomNonce();
        $now 			= new \DateTime("now", new \DateTimeZone('UTC'));
        $timestamp 		= $now->format('YmdHis');
        $signature 		= Hmac::signature($requestId, $timestamp, Constants::API_KEY, Constants::API_SECRET, $randomNonce);

		//Header
		parent::setUrl(Constants::URL.Constants::URL_TOKEN_OBTAIN);
		parent::addHeaderItem('X-Date', $now->format(DATE_RFC1123));
		parent::addHeaderItem('X-Request-ID', $requestId);
		parent::addHeaderItem('X-Auth-Key', Constants::API_KEY);
		parent::addHeaderItem('X-Auth-Code',$signature);
		parent::addHeaderItem('Content-Type','application/hal+json;charset=utf-8');
		parent::addHeaderItem('Accept','application/hal+json');
		
		//Post
		$this->Token = array(
			'grantType'		=> 'api_key',
			'randomNonce'	=> $randomNonce,
		);
		
	}
    public function toArray() {
        return $this->Token;
    }
    
    public function send() {
        return parent::sendGV($this);
    }
}
?>
