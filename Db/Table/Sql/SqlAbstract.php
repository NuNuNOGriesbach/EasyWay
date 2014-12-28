<?php

namespace Ew\Db\Table\Sql;

class AliasRender
{
    public $name;
    public $alias;
    protected static $countInstance;
    
    public function __construct($name, $alias) {
        
        $this->name  = $name;        
        $this->alias = $alias;
        static::$countInstance++;
    }
    
    public function render()
    {
        return $this->name . ' as `' . $this->getAlias() . '`';
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getAlias()
    {
        if($this->name == $this->alias || $this->alias == '' || $this->alias===null ){
            return $this->name;
        }
        return $this->alias;
    }
    
    public function getHash()
    {
        return static::$countInstance;
    }
}

/**
 * Description of SqlAbstract
 *
 * @author nununo
 */
class SqlAbstract {
    protected $_baseName = 'select';
    protected $_sqlBase = "";
    protected $_db = null;
    protected $_table = null;
    protected $_parts = Array('where');
    protected $_registeredHandler = 'db';
        
    protected $_stmt =null;
    
    protected $_mandatoryReplaces = Array(
              
    );
    
    /**
     * Substituição de variaveis customizadas
     * @var type 
     */
    protected $_variables = Array();    
    
    public function __construct($table=null, $dbAdapter=null){
        if($dbAdapter!==null){
            $this->setAdapter($dbAdapter);
        }
        $this->setTable($table);
    }
    
    public function __destruct() 
    {
        if($this->_stmt!==null){
            $this->_db->closeCursor($this->_stmt);
            $this->_stmt = null;
        }
    }
    
    /**
     * Seções do SQL que não geram erros caso sejam substituidas por ''
     * @var Array 
     */
    protected $_optionalReplaces = Array(       
        
    );
    
    protected $_bindParts = Array(
        'where' => Array(),        
    );
    
    protected $_bindValues = Array();
    
    /**
     * Define a tabela principal e os adapters
     * @param string $table Classe estatica da tabela
     */
    public function setTable($table)
    {
        if($table === null || $table==''){
            return;
        }
        $table = $this->_getTableObject($table);
        if(is_object($table)){
            $this->_table = $table;
        }else{
            return;
        }
        if($this->_db === null && @method_exists('$table','getAdapter')){
            $this->setAdapter($table::getAdapter());
        }
        return $this;
    }
    
    public function setAdapter($dbAdapter)
    {        
        if(is_object($dbAdapter)){
            $this->_db = $dbAdapter;
        }else{
            $this->_db = \Ew\Registry::get($dbAdapter);
        }
        
        if($this->_sqlBase==''){
            $this->setSqlBase($this->_db->getSqlBase($this->_baseName));
        }
    }
    
    public function isPrepared()
    {
        return $this->_stmt !== null;
    }
    
    public function prepare()
    {
        if($this->isPrepared()){
            return $this;
        }
        $sql = $this->toString();        
        $this->_stmt = $this->_db->prepare($sql);
       
        return $this;
    }
    
    public function execute(array $params = null)
    {
        if(!$this->isPrepared()){
            $this->prepare();
        }
        if($params===null){
            $params = $this->getBindValues();
        }
        $this->_db->execute($this->_stmt, $params);
        return $this;
    }
    
    public function fetchAll(array $params = null)
    {
        if(!$this->isPrepared()){
            $this->prepare();
        }
        if($params===null){
            $params = $this->getBindValues();
        }
        
        return $this->_db->fetchAll($this->_stmt, $params);        
    }
    
    public function reset($part)
    {
        if(!isset($this->_parts[$part])){
            return $this;
        }
        $this->_parts[$part] = Array();
        if(isset($this->_bindParts[$part])){
            $this->_bindParts[$part] = Array();
        }
        
        if($this->isPrepared()){
            $this->_db->closeCursor($this->_stmt);
            $this->_stmt = null;
        }
        
        return $this;
    }
    
    public function setSqlBase($sqlString, $resetMandatoryParts=false, $resetOptionalParts=false)
    {
        $this->_sqlBase = $sqlString;
        if($resetMandatoryParts){
            $this->_mandatoryReplaces = Array();
        }
        if($resetOptionalParts){
            $this->_optionalReplaces = Array();
        }
    }
    
    public function render()
    {
        return $this->toString();
    }
    
    public function getBindValues()
    {
        $this->_bindValues = Array();
        foreach($this->_bindParts as $bindValues){
            foreach($bindValues as $value){
                $this->_bindValues[] = $value;
            }
        }
        
        if(empty($this->_bindValues)){
            return null;
        }
        return $this->_bindValues;
    }
    
    protected function _getTableObject($table)
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
    
    public function from($table, $fields='*', $alias='')
    {
        $table = $this->_getTableObject($table);
        
        if(is_object($table)){
            if(@method_exists($table, 'getPath')){
                $alias = $table->getAlias($alias);
                $this->_parts['from'][] = new AliasRender($table->getPath(), $alias);           
            }elseif($table instanceof \Ew\Db\Table\Sql\Select){
                $table->setAlias($alias);
                $this->_parts['from'][] = $table;
            }else{
                \Ew\Error::critical(get_class($table) . ' não é um objeto valido para uma tabela sql');
            }            
        }else{
            $render = new AliasRender($table, $alias);
            $this->_parts['from'][] = $render;
            $alias = $render->getAlias();
        }
        
        return $this->fields($alias, $fields);
    }
    
    public function fields($alias, $fields)
    {
        return $this;
    }
    
    public function where($exp, $bindValues=null)
    {
        $this->_parts['where'][] = $exp;
        if($bindValues!==null && strpos($exp, '?')!==false){
            $this->_bindParts['where'][] = $bindValues;
        }
        return $this;
    }
    
    public function having($exp, $bindValues=null)
    {
        $this->_parts['having'][] = $exp;
        if($bindValues!==null){
            $this->_bindParts['having'][] = $bindValues;
        }
        return $this;
    }
    
    public function limit($limit, $offset=null){
        
        $this->_parts['limit'] = Array('LIMIT' =>$limit, 'OFFSET' => $offset);
        return $this;
    }
    
    public function toString()
    {
        if($this->_db===null){
            $this->setAdapter(\Ew\Registry::get($this->_registeredHandler));
        }
        
        $sql = $this->_sqlBase;
        if(empty($sql) || $sql===null){
            \Ew\Error::critical("Comando Sql sem uma estrutura definida");
        }
        foreach($this->_mandatoryReplaces as $section => $function){
            if(strpos($sql, $section)===false){
                \Ew\Error::critical("Sessão obrigatória '$section' não foi definida na estrutura do comando SQL");
            }
            $part = $this->_db->$function($this, $this->_parts, $sql);
            if(empty($part)){
                \Ew\Error::critical("Sessão obrigatória '$section' não foi especificada para o comando SQL");
            }
            $sql = str_replace("[$section]", $part, $sql);
        }
        
        foreach($this->_optionalReplaces as $section => $function){
            $part = $this->_db->$function($this, $this->_parts, $sql);
            $sql = str_replace("[$section]", $part, $sql);
        }
        
        foreach($this->_variables as $var => $value){
            $sql = str_replace("[$var]", $value, $sql);
        }
        
        return trim($sql);
    }
    
    public function getBindParts()
    {
        return $this->_bindParts;
    }
    
    public function unsetBindPart($name, $id)
    {
        unset($this->_bindParts[$name][$id]);
    }
    
    public function setVar($name, $value){
        $this->_variables[$name] = $value;
        return $this;
    }
    
    public function getVar($name){
        $this->_variables[$name];
    }
    
    public function __toString() 
    {
        try{
            return $this->toString();
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
