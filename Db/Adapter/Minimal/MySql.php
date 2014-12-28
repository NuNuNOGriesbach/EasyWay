<?php

namespace Ew\Db\Adapter\Minimal;

/**
 * Description of MySql
 *
 * @author nununo
 */
class MySql extends \Ew\Db\Adapter
{
    
    protected $_db;
    
    public function connect($params)
    {
        try{
            $this->_db = new \PDO('mysql:host='.$params['host'].';port='.$params['port'].';dbname='.$params['schemma'].'', $params['user'], $params['password'], $params['extras']);
        }  catch (\Exception $e){
            $this->error($e->getMessage());
        }
    }
    
            
    
}
