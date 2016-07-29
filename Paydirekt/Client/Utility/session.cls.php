<?php
/**
 * PHP Sessionhandling
 * 
 * Zum persönlichen Mitfürhen des Tokens und der Checkouts des Kunden.
 * 
 * 1.   Der Token ist 3600 Sekunden gültig und wird solange wiederverwendet.
 * 
 * 2.   Um eine z.B. eine Zahlungsgarantie nach dem Rückspung von Paydirekt (redirectUrlAfterSuccess etc.)
 *      abfragen zu können, muss zumindest die checkoutId zwischengespeichtert werden.
 * 
 * Sollten alternative Systeme verwendet werden, ist dieses hier anzupassen.
 * 
 * @autor Michael Töpfer
 * @license MIT
 */

namespace Paydirekt\Client\Utility;

class session {
             
    /**
     * Startet das PHP-Sessionhandling wenn bisher nicht erfolgt.
     * Registiert:
     * $_SESSION['payDirekt']['token']
     * $_SESSION['payDirekt']['checkouts']
     * 
     * @param void
     * @return void
     */
    public static function start (){ 
        if(!session_id()){
            session_start();
        }
        //Benutzte Keys
        if(!isset($_SESSION['payDirekt']['token'])){
            $_SESSION['payDirekt']['token'] = array();
        }
        if(!isset($_SESSION['payDirekt']['checkouts'])){
            $_SESSION['payDirekt']['checkouts'] = array();
        }
    }
    
    /**
     * Speichert einen Paydirekt Token.
     * Wird erstellt in \Paydirekt\Client\Utility\com
     * 
     * @param array $token
     * @return void
     */
    public static function registerToken ($token) {
        $_SESSION['payDirekt']['token'] = $token;
    }
    
    /**
     * Gibt einen zeitlich gültigen Token oder false zurück
     * @param void
     * @return array $token
     */
    public static function getToken () {
        if(!empty($_SESSION['payDirekt']['token']['expires'])){
            if($_SESSION['payDirekt']['token']['expires'] > time()+1800){
                return $_SESSION['payDirekt']['token'];
            }
        }
        return false;
    }
    
    /**
     * Speichert Werte unter dem ArrayKey $hash
     * 
     * @param string hash
     * @param string key
     * @param mixed value
     * @return void;
     */
    public static function setValuesByHash ($hash, $key, $value){
        $_SESSION['payDirekt']['checkouts'][$hash][$key] = $value;      
    }
    
    /**
     * Gibt Werte zu dem ArrayKey $hash zurück 
     * 
     * @param string hash
     * @return mixed
     */
    public static function getValuesByHash($hash){
        return isset($_SESSION['payDirekt']['checkouts'][$hash]) ? $_SESSION['payDirekt']['checkouts'][$hash] : false;
    }
    
    /**
     * Einträge via CheckoutId löschen
     * @param string $checkoutId
     * @return void
     */
     public static function unsetCheckout($checkoutId) {
         foreach ($_SESSION['payDirekt']['checkouts'] as $key => $val) {
             if(isset($val['checkoutId']) && $checkoutId == $val['checkoutId']){
                 unset($_SESSION['payDirekt']['checkouts'][$key]);
             }
         }
     }
}
?>
