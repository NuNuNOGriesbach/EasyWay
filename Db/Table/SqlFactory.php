<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ew\Db\Table;

/**
 *
 * @author nununo
 */
class SqlFactory {
    
    protected $_registeredHandler = 'db';
        
    protected $_sqlTypes = Array(
        'select' => "\Ew\Db\Table\Sql\Select",
        'update' => "\Ew\Db\Table\Sql\Update",
        'insert' => "\Ew\Db\Table\Sql\Insert",
        'delete' => "\Ew\Db\Table\Sql\Delete",
    );
    
    public function __construct($dbAdapter=null) {
        
        if($dbAdapter === null){
            $dbAdapter = \Ew\Registry::get($this->_registeredHandler);
        }
        
        $this->_db = $dbAdapter;
    }
    
    public function setSqlTypes(array $sqlTypes)
    {
        $this->_sqlTypes = $sqlTypes;
    }
    
    public function setSqlType($sqlType, $className)
    {
        $this->_sqlTypes[$sqlType] = $className;
    }
    
    public function factory($type, $table)
    {
        $class = $this->_sqlTypes[$type];
        if(!class_exists($class)){
            \Ew\Error::critical("Classe '$class' definida para $type não foi carregada");
        }
        return new $class($table, $this->_db);
    }
    
    public function getSelect($table)
    {
        return $this->factory('select', $table);        
    }
    
    public function getUpdate($table)
    {
        return $this->factory('update', $table);   
    }
    
    public function getInsert($table)
    {
        return $this->factory('insert', $table);
    }
    
    public function getDelete($table)
    {
        return $this->factory('delete', $table);
    }
        
}
