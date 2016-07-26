<?php
/**
 * PostalAddress
 * Datentyp für Liefer- und Rechnungsadressen.
 * 
 * @autor Michael Töpfer
 * @license MIT
 */
namespace Paydirekt\Client\Data;

class address {
	
	private $Adr;
	
	function __construct() {
		
		/*Plichtfelder*/
		$this->Adr = array(
			'addresseeGivenName'			=> '',
			'addresseeLastName'				=> '',
			'zip'							=> '',
			'city'							=> '',
			'countryCode'					=> 'DE'
		);		
	}
	public function toArray() {
		return $this->Adr;
	}
	
	/**
	 * Batchlauf zu einlesen der Werte über ein Array
	 * @param array $arrValues
	 */
	public function setOpts ($arrValues) {
		foreach ($arrValues as $key => $val) {	
			switch (strtolower($key)) {
				case 'addresseegivenname':
					$this->setGivenName($val);
					break;
				case 'addresseelastname':
					$this->setLastName($val);
					break;
				case 'company':
					$this->setCompany($val);
					break;
				case 'additionaladdressinformation':
					$this->setAdditionalInfo($val);
					break;
				case 'street':
					$this->setStreet($val);
					break;
				case 'streetnr':
					$this->setStreetNr($val);
					break;
				case 'zip':
					$this->setZip($val);
					break;
				case 'city':
					$this->setCity($val);
					break;
				case 'countrycode':
					$this->setCountryCode($val);
					break;
			}
		}
	}
	
	/**
	 * String Pflichtfeld. Maximal 100 Zeichen.
	 * Vorname.
	 */
	public function setGivenName ($name){
		if($name) {
			$this->Adr['addresseeGivenName'] = substr($name, 0,100);
		}
	}
	
	/**
	 * String
	 * Pflichtfeld. Maximal 100 Zeichen.
	 * Nachname.
	 */
	public function setLastName ($name){
		if($name) {
			$this->Adr['addresseeLastName'] = substr($name, 0,100);
		}
	}
	
	/**
	 * String Optional. Maximal 100 Zeichen.
	 * Firmenname.
	 */
	public function setCompany ($name){
		if($name) {
			$this->Adr['company'] = substr($name, 0,100);
		}
	}
	
	/**
	 * String Optional. Maximal 100 Zeichen.
	 * Adresszusatz.
	 */
	public function setAdditionalInfo ($name){
		if($name) {
			$this->Adr['additionalAddressInformation'] = substr($name, 0,100);
		}
	}

	/**
	 * String Optional. Maximal 100 Zeichen.
	 * Der Name der Straße, ohne Hausnummer.
	 */
	public function setStreet ($name){
		if($name) {
			$this->Adr['street'] = substr($name, 0,100);
		}
	}
	
	/**
	 * String Optional. Maximal 10 Zeichen.
	 * Die Hausnummer.
	 */
	public function setStreetNr ($nr){
		if($nr) {
			$this->Adr['streetNr'] = substr($nr, 0,10);
		}
	}
	
	/**
	 * String Pflichtfeld. Maximal 10 Zeichen.
	 * Die Postleitzahl.
	 */
	public function setZip ($nr){
		if($nr) {
			$this->Adr['zip'] = substr($nr, 0,10);
		}
	}
	
	/**
	 * Pflichtfeld. Maximal 100 Zeichen.
	 * Die Stadt.
	 */
	public function setCity ($name){
		if($name) {
			$this->Adr['city'] = substr($name, 0,100);
		}
	}
	
	/**
	 * String Pflichtfeld. 2 Zeichen.
	 * Der Ländercode im ISO 3166-1 Format.
	 */
	public function setCountryCode ($code){
		if($code) {
			$this->Adr['countryCode'] = strtoupper(substr($code, 0,2));
		}
	}
}
?>