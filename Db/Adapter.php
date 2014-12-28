<?php

/*
 * Copyright (C) 2014 nununo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Ew\Db;


/**
 * Description of Adapter
 *
 * @author nununo
 */
class Adapter implements AdapterInterface
{
    protected $_sqlsBase = Array(
        'select' => "SELECT [FIELD_PART] FROM [FROM_PART] [JOIN_PART] [GROUP_PART] [WHERE_PART] [HAVING_PART] [ORDER_PART] [LIMIT_PART]",
        'update' => "UPDATE [FROM_PART] SET [SET_PART] [WHERE_PART] [LIMIT_PART]",
        'insert' => "INSERT INTO [FROM_PART] ([FIELD_PART]) VALUES ([VALUE_PART])",
        'delete' => "DELETE FROM [FROM_PART] [WHERE_PART]",
    );
    
    protected $_statements = Array();
    
    /**
     *
     * @var \PDO
     */
    protected $_db;
    
    /**
     *
     * @var \Ew\Db\Table\SqlFactory
     */
    protected $_sqlFactory = '\Ew\Db\Table\SqlFactory';
    
    public function connect($params)
    {
        \Ew\Error::critical("Não implementado", '\Ew\Db\Exception');
    }
    
    public function __construct(array $params = Array()) {
        $this->configure($params);        
    }
    
    public function configure(array $params)
    {
        foreach($params as $property => $value){
            $this->$property = $value;
        }
        if(!is_object($this->_sqlFactory )){
            $this->_sqlFactory = new $this->_sqlFactory($this);
        }
    }
        
    public function begin()
    {
        $ret = $this->_db->beginTransaction();
        if($ret===false){
            $info = $stmt->errorInfo();
            $this->error($info[2]);
        }
        return $ret;
    }
    
    public function rollback()
    {
        $ret = $this->_db->rollBack();
        if($ret===false){
            $info = $stmt->errorInfo();
            $this->error($info[2]);
        }
        return $ret;
    }
    
    public function commit()
    {
        $ret = $this->_db->commit();
        if($ret===false){
            $info = $stmt->errorInfo();
            $this->error($info[2]);
        }
        return $ret;
    }
    
    public function inTransaction()
    {
        return $this->_db->inTransaction();        
    }
    
    /**
     * Obtem um cursor de execução
     * @param string $sql
     * @return type
     */
    public function prepare($sql)
    {
        $stmt = $this->_db->prepare($sql);
        return $stmt;
    }
    
    /**
     * Fecha um cursor após a execuão
     * @param \PDOStatement $stmt
     */
    public function closeCursor(&$stmt)
    {
        $stmt->closeCursor();
        $stmt = null;
    }
    
    /**
     * Retorna um Array contendo o retorno do cursor
     * @param \PDOStatement $stmt
     * @param Array $params Parametros que serão substituidos pelos ? no SQLoriginal
     */
    public function fetchAll(&$stmt, array $params = null)
    {
        $stmt->execute($params);
        $ret = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if($ret===false){
            $info = $stmt->errorInfo();
            $this->error($info[2]);
        }
        return $ret;
    }
    
    public function execute(&$stmt, array $params = null)
    {
        if($stmt instanceof \PDOStatement){
            return $stmt->execute($params);
        }
        $stmt = $this->prepare($stmt);
        $ret = $stmt->execute($params);
        if($ret===false){
            $info = $stmt->errorInfo();
            $this->error($info[2]);
        }
        return $ret;
    }
            
    public function getLastInsertId($tableClassName)
    {
        if(is_object($tableClassName)){
            return $this->_db->lastInsertId($tableClassName->getAutoincrementFieldName());
        }
        return $this->_db->lastInsertId($tableClassName);
    }
    
    public function getPath($schemma, $tableName)
    {
        if(empty($schemma) || $schemma === null){
            return "`$tableName`";
        }
        return "`$schemma`.`$tableName`";
    }
    
    public function getSqlBase($baseName)
    {
        if(!isset($this->_sqlsBase[$baseName])){
            \Ew\Error::critical("$baseName não é um template válido de SQL", '\Ew\Db\Exception');
        }
        return $this->_sqlsBase[$baseName];
    }
    
    public function renderField($sqlInstance, &$part, &$sql)
    {
        $ret = implode(',', $part['fields']);
        return $ret;
    }
    
    public function renderFieldList($sqlInstance, &$part, &$sql)
    {
        $ret = implode(',', $part['fields']);
        return $ret;
    }
    
    public function renderSet($sqlInstance, &$part, &$sql)
    {
        $ret = '';
        foreach($part['fields'] as $field){
            if($ret!=''){
                $ret .= ',';
            }
            
            $ret .= $field . '=?';
        }
        return $ret;
    }
    
    public function renderValueList($sqlInstance, &$part, &$sql)
    {
        $ret = implode(',', array_fill(0, count($part['fields']), '?'));
        return $ret;
    }
    
    public function renderFrom($sqlInstance, &$part, &$sql)
    {
        $ret = '';
        foreach($part['from'] as $aliasRender){
            if($ret!=''){
                $ret .= ',';
            }
            $ret .= $aliasRender->render();
        }
        
        return $ret;
    }
    
    public function renderInto($sqlInstance, &$part, &$sql)
    {
        $ret = '';
        foreach($part['from'] as $aliasRender){
            if($ret!=''){
                $ret .= ',';
            }
            $ret .= $aliasRender->getName();
        }
        
        return $ret;
    }
    
    public function renderJoin($sqlInstance, &$part, &$sql)
    {
        $ret = '';
        foreach($part['join'] as $alias => $join){
            $ret .= "\n" . $join['type'] . ' JOIN ' . $join['render']->render() . ' ON ';
            $onPart = '';
            foreach($join['on'] as $on){
                if($onPart!=''){
                    $onPart .= ' AND ';
                }
                $onPart .= '(' . $on . ')';
            }
            if($onPart==''){
                \Ew\Error::critical("Cláusula Join para '$alias' não possui uma cláusula ON");
            }
            
            $ret .= $onPart;
        }
        
        return $ret;
    }
    
    public function renderWhere($sqlInstance, &$part, &$sql)
    {
        $ret = '';
        $count = 0;
        $bindParts = $sqlInstance->getBindParts();
        foreach($part['where'] as $id => $exp){
            if($ret != ''){
                $ret .= ' AND ';
            }
            
            if(strpos($exp, '?') !== false){
                
                if(isset($bindParts['where'][$count]) && is_array($bindParts['where'][$count])){
                    $list = "'" . implode("','", $bindParts['where'][$count]) . "'";
                    $exp = str_replace('?', $list, $exp);
                    $part['where'][$id] = $exp;
                    $sqlInstance->unsetBindPart('where',$count);
                    $count++;
                }
            }
            $ret .= "($exp)";
        }
        
        if($ret != ''){
            $ret = 'WHERE ' . $ret;
        }
        
        return $ret;
    }
    
    public function renderHaving($sqlInstance, &$part, &$sql)
    {
        $ret = '';
        $count = 0;
        $bindParts = $sqlInstance->getBindParts();
        foreach($part['having'] as $id => $exp){
            if($ret != ''){
                $ret .= ' AND ';
            }
            
            if(strpos($exp, '?') !== false){
                
                if(isset($bindParts['having'][$count]) && is_array($bindParts['having'][$count])){
                    $list = "'" . implode("','", $bindParts['having'][$count]) . "'";
                    $exp = str_replace('?', $list, $exp);
                    $part['having'][$id] = $exp;
                    $sqlInstance->unsetBindPart('having',$count);                    
                    $count++;
                }
            }
            $ret .= "($exp)";
        }
        
        if($ret != ''){
            $ret = 'HAVING ' . $ret;
        }
        
        return $ret;
    }
    
    public function renderGroup($sqlInstance, &$part, &$sql)
    {
        $ret = '';
        if(!empty($part['group'])){
            $list = implode(',',$part['group']);
            $ret = "GROUP BY $list";
        }
        
        return $ret;
    }
    
    public function renderOrder($sqlInstance, &$part, &$sql)
    {
        $ret = '';
        foreach($part['order'] as $order){
            if($ret!=''){
                $ret .= ',';
            }
            if($order['nullsLast']){
                $ret .= 'isnull(' . $order['field'] . ') asc,';
            }
            $ret .= $order['field'] . ' ' . $order['direction'];
            
        }
        if($ret!=''){
            $ret = 'ORDER BY ' . $ret;
        }
        return $ret;
    }
    
    public function renderLimit($sqlInstance, &$part, &$sql)
    {
        $ret = '';
        if(isset($part['limit']['LIMIT']) && $part['limit']['LIMIT'] !== null){
            $ret .= 'LIMIT ' . $part['limit']['LIMIT'];
        }
        if(isset($part['limit']['OFFSET']) && $part['limit']['OFFSET'] !== null){
            $ret .= ', ' . $part['limit']['OFFSET'];
        }
        return $ret;
    }
    
    public function factory($sqlType, $table){
        return $this->_sqlFactory->factory($sqlType, $table);
    }
    
    protected function error($msg)
    {
        \Ew\Error::critical($msg , '\Ew\Db\Exception') ;
    }
    
    public function getTableObject($table)
    {
        if(is_object($table)){
            return $table;
        }
        if(substr($table,0,1)=='\\'){
            if(@class_exists($table)){
                $table = new $table();
                return $table;
            }
        }
        return $table;
    }
    
    public function getTableName($table)
    {
        $table = $this->getTableObject($table);
        if(is_object($table)){
            return $table->getPath(); 
        }
        return  $table;        
    }
    
    protected function _transalteColumnType($typeDesc)
    {
        $tmp = explode('(', $typeDesc);
        $type = $tmp[0];
        $intPart = null;
        $floatPart = null;
        if(isset($tmp[1])){
            $tmp2 = explode(',', substr($tmp[1],0,-1));
            $intPart = $tmp2[0];
            if(isset($tmp2[1])){
                $floatPart = $tmp2[1];
            }
        }
        
        $type = strtolower($type);
        switch($type){
            case 'int':
            case 'float':    
            case 'decimal':    
            case 'bigint':
            case 'double':
            case 'smallint':
            case 'mediumint':    
            case 'tinyint':  
            case 'numeric':      
                $translateType = 'number';
                break;
            case 'char': 
            case 'varchar': 
            case 'text':    
            case 'longtext':
            case 'mediumtext':
            case 'tinytext':
                $translateType = 'text';
                if($intPart==1){
                    $translateType = 'boolean';
                }elseif($intPart===null){
                    $translateType = 'textarea';
                }
                break;
            case 'date': 
                $translateType = 'date';
                break;
            case 'datetime': 
                $translateType = 'datetime';
                break;
            default:
                $translateType = 'text';
                
        }
        
        return Array(
            'type' => $type,
            'kind' => $translateType,
            'size' => $intPart,
            'precision' => $floatPart,
        );
    }
    public function getColumns($table)
    {
        $tabeName = $this->getTableName($table);
        
        $select = $this->factory('select', $table);
        $select->setSqlBase('show columns from ' . $tabeName, true, true);
        
        $columns = $select->fetchAll();
        $return = Array();
        
        foreach($columns as $column => $info)
        {
            $field = $info['Field'];
            $type = $this->_transalteColumnType($info['Type']);
            $return[$field] = Array(
                'name' => $field,
                'type' => $type['type'],
                'kind' => $type['kind'],
                'size' => $type['size'],
                'precision' => $type['precision'],
                'required' => !$info['Null'],
                'primary' => $info['Key']=='PRI',
                'unique' => $info['Key']=='UNI',
                'indexed' => $info['Key'] !== null,
                'auto_increment' => strpos($info['Extra'], 'auto_increment')!==false,         
                
            );
        }
        
        return $return;
        
    }
}
