<?php

namespace Ew\Helper;

/**
 * Description of Array
 *
 * @author nununo
 */
class ArrayGetter {
    public static function getArray($element)
    {
        if(is_array($element)){
            return $element;
        }
        return Array($element);
    }
}
