<?php
namespace app;
class Config
{
    public static $include_path = '/home/nununo/NetBeansProjects/Mantrask/';
    public static $app = 'app';

    public static $databases = Array(
        'db' => Array(
            'host'=>'localhost',
            'port'=>3360,
            'schemma'=>'test',
            'user'=>'root',
            'password'=>'teste123',
            'extras'=> Array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',) //PDO params
        ),
    );
}