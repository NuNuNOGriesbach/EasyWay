<?php
namespace Ew\Helper;

/**
 * Description of FormatString
 *
 * @author nununo
 */
class FormatString {
    /**
     * Pega um nome de controller vindo de uma _url e transforma em CamelCase
     * Ex. nota-fiscal para NotaFiscal
     * @param type $name
     * @return string
     */
    public static function urlToCamelCase($name)
    {
        $result = strtoupper(substr($name,0,1));
        $upper=false;
        for($x=1; $x<strlen($name); $x++){
            $digit = substr($name, $x,1);
            if($upper){
                $digit = strtoupper($digit);
            }
            if($digit=='-'){
                $upper = true;
                $digit='';
            }
            
            $result.=$digit;
        }
        return $result;
    }
}
