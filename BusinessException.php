<?php
/**
 * Created by PhpStorm.
 * User: nununo
 * Date: 22/03/14
 * Time: 12:39
 */

namespace Ew;


class BusinessException extends \Phalcon\Exception{
    public $isBusiness = true;
} 