<?php
/**
 * PayDirekt intregration
 * 
 * SANDBOX_API_KEY = "e81d298b-60dd-4f46-9ec9-1dbc72f5b5df";
 * SANDBOX_API_SECRET = "GJlN718sQxN1unxbLWHVlcf0FgXw2kMyfRwD0mgTRME=";
 *  
 * @autor Michael Töpfer
 * @license MIT
 */

namespace Paydirekt\Client;

if(defined('WORKSPACE')){
    $_WS = WORKSPACE;
} else {
    $_WS = '';
}

if($_WS == 'live') {

	/*Live*/
	class Constants {
    	const URL               = 'https://api.paydirekt.de'; 
    	const API_KEY           = 'Dein Api Key';
    	const API_SECRET        = 'Dein Api Secret';
        const URL_TOKEN_OBTAIN  = '/api/merchantintegration/v1/token/obtain';
        const URL_CHECKOUT      = '/api/checkout/v1/checkouts';	
		const URL_RESPONSE		= 'https://deineUrl/Paydirekt/beispiel_response.php';
		const ORDER_NOTE    	= 'Deine Firma sagt: Vielen Dank :)';
    }

} else {
	
	/*Testsystem*/
    class Constants {
    	const URL               = 'https://api.sandbox.paydirekt.de'; 
    	const API_KEY           = 'e81d298b-60dd-4f46-9ec9-1dbc72f5b5df';
    	const API_SECRET        = 'GJlN718sQxN1unxbLWHVlcf0FgXw2kMyfRwD0mgTRME=';
        const URL_TOKEN_OBTAIN  = '/api/merchantintegration/v1/token/obtain';
        const URL_CHECKOUT      = '/api/checkout/v1/checkouts';
		const URL_RESPONSE      = 'http://yakstore.de/aaa/beispiel_response.php';
		const ORDER_NOTE    	= 'Deine Firma sagt: Vielen Dank :)';
 
    }
}


?>