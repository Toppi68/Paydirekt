<?php
/**
 * payDirekt Communication
 * 
 * @autor Michael Töpfer
 * @license MIT
 */
  
namespace Paydirekt\Client\Utility;

use Paydirekt\Client\Constants;
use Paydirekt\Client\Data\gvToken;
use Paydirekt\Client\Utility\responseMessages;
use Paydirekt\Client\Utility\session;

require_once('Paydirekt/Client/Data/gvToken.cls.php');
require_once('Paydirekt/Client/Utility/responseMessages.cls.php');

class com extends responseMessages {
 				
	/**
	 * Sendet ein Obejkt-Geschäftsvorfall (Klasse aus \Data\gv*.cls.php)
	 * 
     * @param object
	 * @return array
	 */
	protected function sendGV ( $objGV ) {
		
        $hash           = $objGV->getHash();
		$headerVars		= $objGV->getHeaderVars();
		$postVars 		= $objGV->toArray();
		$url			= $objGV->getUrl();
   
        /**
         * PostVars
         * Wichtige Werte für den Rücksprung aus Paydirekt
         * zwischenspeichern, sofern zu diesem GV vorhanden.
         */
        foreach ($postVars as $key => $val) {
            switch (strtolower($key)) {
                case 'merchantcustomernumber':
                    session::setValuesByHash($hash, 'merchantCustomerNumber', $val);
                    break;
                case 'merchantorderreferencenumber':
                    session::setValuesByHash($hash, 'merchantOrderReferenceNumber', $val);
                    break;
                case 'merchantinvoicereferencenumber':
                    session::setValuesByHash($hash, 'merchantInvoiceReferenceNumber', $val);
                    break;
            }
        }
        
        /**
         * HeaderVars
         * Prüfen ob Token benötigt wird.
         * Bei ContentType json -> encoden
         */
		foreach ($headerVars as $key => $val) {	
			//Auth benötigt?
			$pos = stripos($val, 'Authorization: ');
			if($pos !== false){
				//Token benötigt
				$token = self::getToken();
				if(!$token['success']) {
					return $token;
				}
				$headerVars[$key] = ('Authorization: Bearer '.$token['access_token']);
			}
			//ContentType
			$pos = stripos($val, 'Content-Type: ');
			if($pos !== false){
				$pos = stripos($val, 'json');
				if($pos !== false){
					$postVars = json_encode($postVars);
				}
			}
		}
        
		//Senden			
		$req = self::sendCurl($url, $headerVars, $postVars);
		
		//Antworten parsen
		$req['response'] = parent::parse($objGV::Type, $req['response']);
        
        //Evtl. checkoutId speichern
        if(isset($req['response']['checkoutId'])){
            session::setValuesByHash($hash, 'checkoutId', $req['response']['checkoutId']);
        }
        
		return $req;
	}
	
	/**
	 * Gibt einen gültigen Token für die Auth. Bearer zurück.
     * @param void
     * @return array Token
	 */
	private function getToken() {
		
        if($token = session::getToken()){
            return $token;
        }
		//Anfordern
		$req = self::sendGV(new gvToken());

		//Als Session speichern		
		if($req['success']) {
            $token = array(
				'access_token'	=> $req['response']['access_token'],
				'token_type'	=> $req['response']['token_type'],
				'expires_in'	=> $req['response']['expires_in'],
				'expires'		=> $req['response']['expires_in']+time(),
				'scope'			=> $req['response']['scope'],
				'jti'			=> $req['response']['jti'],
				'success'		=> true
			 );			
		} else{
			$token = array(
				'access_token'	=> '',
				'token_type'	=> '',
				'expires_in'	=> 0,
				'expires'		=> 0,
				'scope'			=> '',
				'jti'			=> '',
				'success'		=> true
			);
		}
        session::registerToken($token);
		return $token;		
	}
	
	/**
	 * Sendet irgendwas via HTTP und CURL
	 * @param string $url
	 * @param array $arrHeader Headerinformationen
	 * @param mixed $postVar PostVars
	 * @return array
	 */
	private function sendCurl ($url="", $arrHeader='', $postVar='') {
		
		$ret = array(
			'success'		=> false,
			'response'		=> '',
			'error'			=> ''
		);
		if(!$url){
			$ret['error'] = 'URL not set.';
			return $ret;
		}
		
        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 2);
        //curl_setopt($request, CURLOPT_CAINFO, realpath(dirname(__FILE__)) ."/cacert.pem");
        
        if($arrHeader) {
        	curl_setopt($request, CURLOPT_HTTPHEADER, $arrHeader);
		}
		if($postVar){
        	curl_setopt($request, CURLOPT_POST, 1);
        	curl_setopt($request, CURLOPT_POSTFIELDS, $postVar);			
		}
        $response = curl_exec($request);
        $responseCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
		
        if ($responseCode != 200 && $responseCode != 201) {
			$ret['error'] = ($responseCode > 0 ? "Unexpected status code " .$responseCode .": " .$response : "");
            $ret['error'] .= (curl_error($request) ? curl_error($request) : "");
			$ret['success'] = false;
        } else {
			$ret['response'] = $response;
			$ret['success']	= true;
        }
		curl_close($request);
		return $ret;
	}
 }
 ?>