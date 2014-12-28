<?php

namespace Ew\Db\Table;

/**
 * Description of Result
 *
 * @author nununo
 */
class Result implements \ArrayAccess , \Iterator, \Ew\Db\Table\Interfaces\Result {
   
    /**
     * Data
     *
     * @var array
     * @access private
     */
    protected $_data = [];
    protected $_cleanData = [];
    protected $_changed = [];
    /**
     *
     * @var \Ew\Db\Table\TableAbstract
     */
    protected $_table = null;
    
    ## Parametros do result
    /**
     * Permite que o Result possa saber quais campos foram alterados 
     * @var type 
     */
    protected $_preserveCleanData = true;  
    
    /**
     * Itera todos os resultados em uma unica instancia, caso seja marcado como false,
     * irá gerar uma nova instancia para cada linha
     * @var type 
     */
    protected $_fastInteract = true;
    
    
    ##Parametros reservados
    protected $_isNew = false;
    protected $_params = Array();
    
    public function __construct(array $assossiativeArray, $table, $resultParams = Array(), $isNew=false) {
        $this->_processParams($resultParams);
        if(empty($assossiativeArray) || $assossiativeArray===null){
            $assossiativeArray = Array();            
        }else{            
            if($this->_preserveCleanData){
                $this->_cleanData = $assossiativeArray;
            }            
        }
        
        $this->_isNew = $isNew;
        $this->_data = $assossiativeArray;
        $this->_table = $table;
        $this->_params = $resultParams;
    }
    
    protected function _processParams($resultParams)
    {
        foreach($resultParams as $param => $value){
            if(isset($this->$param )){
                $this->$param = $value;
                continue;
            }
            $pparam = "_$param";
            if(isset($this->$pparam )){
                $this->$pparam = $value;
                continue;
            }
        }
    }

    function rewind() {
        reset($this->_data);
        return $this;
    }

    function current() {
        if($this->_fastInteract){
            return $this;
        }
        return $this->copy();
    }
    
    /**
     * Como existe uma unica instancia para todo conteudo de um resultset, esta
     * função é necessária para permitir a comparação entre duas linhas
     * @return \self
     */
    function copy()
    {
        return new self(Array(current($this->_data)), $this->_table, $this->_params, $this->_isNew);
    }

    function key() {
        return key($this->_data);
    }

    function next() {
       next($this->_data);
       return $this;
    }

    function valid() {
        return key($this->_data) !== null;
    }

    /**
     * Get a _data by key
     *
     * @param string The key _data to retrieve
     * @access public
     */
    public function &__get ($key) {
        $res = current($this->_data);
        return $res[$key];
    }

    /**
     * Assigns a value to the specified _data
     * 
     * @param string The _data key to assign the value to
     * @param mixed  The value to set
     * @access public 
     */
    public function __set($key,$value) {
        $cur = $this->key();
        if(!isset($this->_changed[$cur]) || !is_array($this->_changed[$cur])){
            $this->_changed[$cur]=[];
        }
        if($this->$key != $value){
            $this->_changed[$cur][$key] = $value;
        }
        $this->_data[$cur][$key] = $value;
    }

    /**
     * Whether or not an _data exists by key
     *
     * @param string An _data key to check for
     * @access public
     * @return boolean
     * @abstracting ArrayAccess
     */
    public function __isset ($key) {
        return isset(current($this->_data)[$key]);
    }

    /**
     * Unsets an _data by key
     *
     * @param string The key to unset
     * @access public
     */
    public function __unset($key) {
        unset(current($this->_data)[$key]);
    }

    /**
     * Assigns a value to the specified offset
     *
     * @param string The offset to assign the value to
     * @param mixed  The value to set
     * @access public
     * @abstracting ArrayAccess
     */
    public function offsetSet($offset,$value) {
        if (is_null($offset)) {
            current($this->_data)[] = $value;
        } else {
            current($this->_data)[$offset] = $value;
        }
    }

    /**
     * Whether or not an offset exists
     *
     * @param string An offset to check for
     * @access public
     * @return boolean
     * @abstracting ArrayAccess
     */
    public function offsetExists($offset) {
        return isset(current($this->_data)[$offset]);
    }

    /**
     * Unsets an offset
     *
     * @param string The offset to unset
     * @access public
     * @abstracting ArrayAccess
     */
    public function offsetUnset($offset) {
        if ($this->offsetExists($offset)) {
            unset(current($this->_data)[$offset]);
        }
    }

    /**
     * Returns the value at specified offset
     *
     * @param string The offset to retrieve
     * @access public
     * @return mixed
     * @abstracting ArrayAccess
     */
    public function offsetGet($offset) {
        return $this->offsetExists($offset) ? current($this->_data)[$offset] : null;
    }
    
    public function __call($method, $args) {
       
        $params = Array();
        $params[] = $this;
        foreach($args as $param){
            $params[] = $param;
        }
        return call_user_func_array(Array($this->_table, $method), $params);  
        
    }
    
    public function getChanged()
    {
        $cur = $this->key();
        if(!isset($this->_changed[$cur])){
            return Array();
        }
        
        return $this->_changed[$cur];
    }
    
       
    public function isNew()
    {
        return $this->_isNew;
    }
    
    public function isEmpty()
    {
        return empty($this->_data);
    }
    
    public function setData($data, array $except = Array()){
        if(is_object($data)){
            $data = $data->currentRowToArray();
        }
        foreach($data as $key => $value){
            if(in_array($key, $except)===false){
                $this->$key = $value;
            }
        }
        return $this;
    }
    
    /**
     * Define como nulos todos, ou uma lista de campos
     * @param array $fieldsToClear
     * @return \Ew\Db\Table\Result
     */
    public function clear(array $fieldsToClear = null){
        if($fieldsToClear===null){           
            $fieldsToClear = array_keys($this->currentRowToArray());
        }
        foreach($fieldsToClear as $field){
            $this->$field=null;
        }
        return $this;
    }
            
    public function validateSave()
    {}
    
    public function validateUpdate()
    {}
    
    public function validateInsert()
    {}
    
    public function validateDelete()
    {}
    
    public function beforeSave()
    {}
    
    public function beforeUpdate()
    {}
    
    public function beforeInsert()
    {}
    
    public function beforeDelete()
    {}
    
    public function afterSave($id)
    {}
    
    public function afterUpdate($id)
    {}
    
    public function afterInsert($id)
    {}
    
    public function afterDelete($id)
    {}
    
    public function save()
    {
        $this->beforeSave();
        $this->validateSave();
        
        if($this->isNew()){
            $id = $this->insert();
        }else{
            $id = $this->update();
        }
        
        $this->afterSave($id);
        return $id;
    }
    
    public function insert()
    {
        $this->beforeInsert();
        $this->validateInsert();
        
        $id = $this->_table->insert($this);
        
        $this->afterInsert($id);
        return $id;
    }
    
    public function update()
    {
        $this->beforeUpdate();
        $this->validateUpdate();
        
        $id = $this->_table->update($this);
        
        $this->afterUpdate($id);
        return $id;
    }
    
    public function delete()
    {
        $id = $this->getId();
        $this->beforeDelete();
        $this->validateDelete();
        
        $ret = $this->_table->delete($this);
        
        $this->afterDelete($id);
        return $ret;
    }
    
    public function refresh()
    {
        return $this->_table->refresh($row);
    }
    
    public function getId()
    {
        $idKey = $this->_table->getAutoincrementFieldName();
        return $this->$idKey;
    }
    
    public function getTable()
    {
        return $this->_table;
    }

    public function toArray()
    {
        return $this->_data;
    }
    
    public function currentRowToArray()
    {
        return current($this->_data);
    }
    
    protected function _notValid($fieldList = Array(), $mensagem=null)
    {
        \Ew\Error::notValid($this->_table, \Ew\Helper\ArrayGetter::getArray($fieldList), $mensagem);
    }
}