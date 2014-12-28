<?php

namespace Ew;

/**
 * Description of Registry
 *
 * @author nununo
 */
class Registry {
    protected static $_registry = Array();
    
    public static function set($name, $value)
    {
        static::$_registry[$name] = $value;
    }
    
    public static function get($name)
    {
        if(!static::has($name)){
            Error::critical($name . ' não está definido');
        }
        return static::$_registry[$name];
    }
    
    public static function has($name)
    {
        return isset(static::$_registry[$name]);
    }
}
