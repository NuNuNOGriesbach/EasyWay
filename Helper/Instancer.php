<?php

namespace Ew\Helper;

/**
 * Description of Instancer
 *
 * @author nununo
 */
class Instancer {
    public static function getInstance($classNameOrObject)
    {
        if(is_object($classNameOrObject)){
            return $classNameOrObject;
        }
        
        return new $classNameOrObject();
    }
}
