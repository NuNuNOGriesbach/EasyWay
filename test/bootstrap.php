<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author nununo
 */
// TODO: check include path
//ini_set('include_path', ini_get('include_path'));

// put your code here
?>
<?php
require_once('ConfigTest.php');

set_include_path(get_include_path() . PATH_SEPARATOR . \app\Config::$include_path);

function test_autoload($class_name) {
    
    $class_name = str_replace('\\', '/', $class_name);
    //echo "include $class_name.php\n";
    include_once $class_name . '.php';
    
}


spl_autoload_register('test_autoload');


$db = new \Ew\Db\Adapter\Minimal\MySql();
$db->connect(\app\Config::$databases['db']);
\Ew\Registry::set('db', $db); 
 