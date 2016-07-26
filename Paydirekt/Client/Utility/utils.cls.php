<?php
/**
 * Ein paar Hilfsmittel
 * 
 * @autor Michael Töpfer
 * @license MIT
 */
namespace Paydirekt\Client\Utility;

class utils {
    
    /**
     * Replaced eine Zeichenkette in gültige Sepazeichen
     * Üngültige Zeichen werden dabei durch das "?" ersetzt.
     * 
     * Gültige Zeichen sind:
     *      a b c d e f g h i j k l m n o p q r s t u v w x y z
     *      A B C D E F G H I J K L M N O P Q R S T U V W X Y Z
     *      0 1 2 3 4 5 6 7 8 9
     *      ' : ? , - ( + . ) /
     *
     * @param string $value
     * @return string
     */
     
    public static function getValidSepaString($value){
        $replaces = preg_replace('/[a-z]|[0-9]|[\'\:\?\,\-\(\+\.\)\/]/i', '', $value);
        if($replaces){
            for($i=0; $i < strlen($replaces); $i++){
                $value = str_ireplace(substr($replaces,$i,1), '?', $value);
            }
        }
        return $value;
    }
    
    /**
     * Prüft ob ein String ein Json-Objekt ist
     *
     * @param string $string
     * @return bool
     */
    public static function is_json($string){        
        return is_string($string) && is_array(json_decode($string,true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
    
    /**
     * Gibt eine Post oder Get-Variable escaped zurück
     * @param string $name
     * @param mixed $default
     * @return string
     * 
     */
    public static function getParam ($name="GET_OR_POST_PARAM", $default="") {
        $val = null;
        $val = isset($_GET[$name]) ? $_GET[$name] : $val;
        $val = isset($_POST[$name]) ? $_POST[$name] : $val;
        if($default !== ''){
            if($val === null){
                $val = $default;
            }
        }
        return $val;
    }
}

?>