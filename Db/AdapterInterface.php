<?php

namespace Ew\Db;

class Exception extends \Ew\Exception{}

/**
 * Description of Interface
 *
 * @author nununo
 */
interface AdapterInterface {
    /**
     * 
     * @param type $params Ex. Array(
            'host'=>'localhost',
            'port'=>3360,
            'schemma'=>'test',
            'user'=>'root',
            'password'=>'teste123',
            'extras'=> Array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',) //PDO params
        )
     */
    public function connect($params);
    public function execute(&$stmt, array $params = null);
    public function prepare($sql);
    public function fetchAll(&$stmt, array $params = null);   
    public function getLastInsertId($tableName);
    public function closeCursor(&$stmt);  
    public function getPath($schemma, $tableName);
    
    public function getSqlBase($baseName);
}
