<?php
/**
 * Item
 * Ein Item ist eine Warenkorbposition.
 * und kann beliebig häufig an eine Order
 * übergeben werden
 * 
 * @autor Michael Töpfer
 * @license MIT
*/
namespace Paydirekt\Client\Data;

class item {
	
	private $Item;
	
	function __construct() {
		
		/*Plichtfelder*/
		$this->Item = array(
			'quantity'	=> 1,
			'name'		=> '',
			'price'		=> 0
		);		
	}
	public function toArray() {
		return $this->Item;
	}
	
	/**
	 * Batchlauf zu einlesen der Werte über ein Array
	 * @param array $arrValues
	 */
	public function setOpts ($arrValues) {
		foreach ($arrValues as $key => $val) {
			switch (strtolower($key)) {
				case 'quantity':
					$this->setQuantity($val);
					break;
				case 'name':
					$this->setName($val);
					break;
				case 'ean':
					$this->setEAN($val);
					break;
				case 'price':
					$this->setPrice($val);
					break;
			}
		}
	}	
	
	/**
	 * Number Pflichtfeld. Ganzzahlig. Mindestens 1.
	 * Die Anzahl der Positionen für diesen Artikel.
	 * 
	 * @param int $quantity
	 */
	public function setQuantity ($quantity){
		if(!is_numeric($quantity) || $quantity < 1){
			return;
		}
		$this->Item['quantity'] = abs(floor($quantity));
	}
	
	/**
	 * String Pflichtfeld. Maximal 100 Zeichen.
	 * Die Bezeichnung des Artikel.
	 * 
	 * @param string $name
	 */
	public function setName ($name){
		if($name){
			$this->Item['name'] = substr($name, 0,100);
		}
	}
	
	/**
	 * String Optional. Maximal 100 Zeichen.
	 * Die International Article Number (EAN bzw. GTIN) des Artikels.
	 * 
	 * @param string $ean
	 */
	public function setEAN ($ean){
		if($ean) {
			$this->Item['ean'] = substr($ean, 0,100);
		}
	}
	
	/**
	 * Number Pflichtfeld. Positiv oder Negativ. Maximal 4 Nachkommastellen.
	 * Der Einzelpreis eines Artikels, inkl. Steuern. Bei Gutschriften 
	 * (z. B. in Form vom Gutscheinen) kann der Betrag auch negativ sein.
	 * 
	 * @param float $price
	 */
	public function setPrice ($price){
		if(!is_numeric($price)){
			return;
		}
		$this->Item['price'] = $price;
	}
}
?>