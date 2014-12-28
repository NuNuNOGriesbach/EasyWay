<?php

namespace Ew\Db\Table;

class NotFoundException extends \Ew\Exception{}
class NotValidException extends \Ew\Exception{}
/**
 * Description of Abstract
 *
 * @author nununo
 */
class TableAbstract {
    //Handler de conexão padrão obtido de \Ew\Registry
    protected $_registeredHandler = 'db';
    protected $_resultClassName = 'Ew\Db\Table\Result';
    protected $_defaultsToResult = Array(); //modifica propriedades do resultado ($_resultClassName) no momento de instanciar
            
    protected $_schemma = null;
    protected $_name = null;
    protected $_defaultAlias = null;
    protected $_primary = Array('id');
    protected $_autoIncrement = 'id';
    
    //Modos de funcionamento
    
    /**
     * Se existe alguma trigger que altere a tabela após o update, esta propriedade
     * deve ser ativada. Causa uma releitura da linha na tabela.
     * Como a maior parte das tabelas costuma ser livre de triggers e outras ameaças,
     * por padrão é definida como false.
     * @var boolean 
     */
    protected $_refreshOnUpdate = false;
    
    /**
     * Se existe algum valor default para campo, se uma trigger altera o registro
     * ou se é necessário acessar o ID  logo após o insert, marque como true esta propriedade.
     * SE sua tabela não precisa destes recursos, marque como false.
     * @var boolean 
     */
    protected $_refreshOnInsert = true;   
    
    /**
     * Se você gostaria de ter acesso aos valores que foram excluídos após o delete
     * deixe esta variavel como false. Senão, o resultado será limpo após o delete.
     * @var boolean 
     */
    protected $_refreshOnDelete = false;
    
    /**
     * Utilizar o campo de autoincremento para fazer o refresh costuma ser mais rapido
     * do que usar uma chave primaria composta, mas, se for necessário, marque como false.
     * @var boolean 
     */
    protected $_refreshUseAutoIncrementKey = true;
    
    /**
     * Dicionário de dados
     * @var \Ew\Db\Table\DataDictionary
     */
    protected $_dataDic = 'Ew\Db\Table\DataDictionary';
    
    public $initParams = Array();
    
    // Uso interno
    /**
     * Conexão com a base
     * @var \Ew\Db\Adapter
     */
    protected $_db;
    protected $_initialized = false;
    
    public function __construct($params = Array()) {
        $this->initialize($params);
    }
    
    /**
     * ponto de customização. os parametros podem ser invocados pela funcao initialize, ou na propriedade self::initParams
     * @param type $params
     */
    public function init($params = Array())
    {
        
    }
    
    public function initialize($params = Array())
    {
        if($this->_initialized){
            return;
        }
        if(empty($params)){
            $params = $this->initParams;
        }
        $this->connect();
        $this->init($params);
        $this->_initialized = true;
        //Inicializa dicionário de dados
        $this->_dataDic = \Ew\Helper\Instancer::getInstance($this->_dataDic, $this);
    }
    
    public function connect($dbRegisteredHandler=null)
    {
        if($dbRegisteredHandler===null){
            $dbRegisteredHandler = $this->_registeredHandler;
        }
        $this->_db = \Ew\Registry::get($dbRegisteredHandler);
    }
    
    public function getAdapter()
    {
        if($this->_db !== null){
            return $this->_db;
        }
        return \Ew\Registry::get($this->_registeredHandler);
    }
    
    public function setAdapter($dbAdapter)
    {
        $this->_db = $dbAdapter;         
    }
    
    public function setResultClassName($className)
    {
        if(!class_exists($class_name)){
            \Ew\Error::critical($className . ' não está definida');
        }
        $this->_resultClassName = $className;
    }
    
    public function getPath()
    {
        return $this->_db->getPath($this->_schemma, $this->_name);
    }
    
    public function getAlias($alias='')
    {
        if($alias != '' && $alias !== null){
            $this->_defaultAlias = $alias;
            return $alias;
        }
        if($this->_defaultAlias!='' && $this->_defaultAlias!==null){
            return $this->_defaultAlias;
        }
        if($this->_name!='' && $this->_name!==null){
            return $this->_name;
        }
        \Ew\Error::critical("Tabela não possui um nome");
    }
    
    
        
    public function save(&$row)
    {
        if($row->isNew()){
            return $row->insert();
        }
        return $row->update();
    }
    
    public function update(&$row)
    {        
        $update = $this->_db->factory('update', $this);
        $update->from($this);
        foreach(\Ew\Helper\ArrayGetter::getArray($this->_primary) as $field){
            $update->where("$field = ?", $row->$field);
        }
        $update->setFieldsAndValues($row->getChanged());
        $update->execute();
        if($this->_refreshOnUpdate){
            $this->refresh($row, $row[$this->_autoIncrement]);
        }
        return $row[$this->_autoIncrement];
    }
    
    public function insert(&$row)
    {
        $insert = $this->_db->factory('insert', $this);
        $insert->into($this);
        $insert->setFieldsAndValues($row->getChanged());
        $insert->execute();
        $ret = $this->_db->getLastInsertId($this);
        if($this->_refreshOnInsert){
            $this->refresh($row, $ret);
        }        
        return $ret;
    }
    
    public function refresh($row, $id=null)
    {
        if($id===null){
            $id = $row->getId();
        }
        if($this->_refreshUseAutoIncrementKey){
            $refreshedRow = $this->findById($id);
        }else{
            $select = $this->select();
                foreach(\Ew\Helper\ArrayGetter::getArray($this->_primary) as $field){
                $select->where("$field = ?", $row->$field);
                $refreshedRow = $this->fetchRow($select);
            }
        }
        
        $row->setData($refreshedRow);
        
    }
    
    public function delete(&$row)
    {
        $row->beforeDelete();
        $row->validateDelete();
        $delete = $this->_db->factory('delete', $this);
        $delete->into($this);
        foreach(\Ew\Helper\ArrayGetter::getArray($this->_primary) as $field){
            $delete->where("$field = ?", $row->$field);
        }
        $delete->execute();
        if($this->_refreshOnDelete){
            $row->clear();
        }
        return true;
    }
    
    /**
     * Obtem uma instancia de Select para a tabela
     * @return \Ew\Db\Table\Sql\Select
     */
    public function select()
    {
        return $this->_db->factory('select', $this);
    }
    
    /**
     * Retorna um registro pelo campo de autoincremento
     * @param type $id
     * @return \Ew\Db\Table\Result
     */
    public function getById($id)
    {
        static $select = null;
        if($select===null){
            $select = $this->select();
            $select->where($this->_autoIncrement .' = ?', $id);
        }
        //Chamada otimizada, reutiliza uma query previamente preparada após a segunda vez.
        return $this->fetchRow($select, Array($id));
    }
    
    /**
     * Retorna verdadeiro se existir um registro com campo de autoincremento
     * @param type $id
     * @return \Ew\Db\Table\Result
     */
    public function canFindById($id)
    {
        $ret = $this->getById($id);
        return !$ret->isEmpty();
    }
    
    /**
     * Retorna um registro pelo campo de autoincremento ou dispara uma exceção
     * @param type $id
     * @return \Ew\Db\Table\Result
     */
    public function findById($id)
    {
        $ret = $this->getById($id);
        if($ret->isEmpty()){
            $this->_notFound(Array($this->_autoIncrement => $id));
        }
        return $ret;
    }
    
    /**
     * 
     * @param \Ew\Db\Table\Sql\Select $select
     * @param array $bindParams para Selects já preparados, insere a quente os valores nos placeholders
     * @return \Ew\Db\Table\Result
     */
    public function fetchRow($select, $bindParams = null)
    {
        $select->limit(1);
        return new $this->_resultClassName($select->fetchAll($bindParams), $this, $this->_defaultsToResult);
    }
    
    /**
     * 
     * @param \Ew\Db\Table\Sql\Select $select
     * @param array $bindParams para Selects já preparados, insere a quente os valores nos placeholders
     * @return \Ew\Db\Table\Result
     */
    public function fetchAll($select, $bindParams = null)
    {
        return new $this->_resultClassName($select->fetchAll($bindParams), $this, $this->_defaultsToResult);
    }
    
    /**
     * 
     * @param \Ew\Db\Table\Sql\Select $select
     * @param array $bindParams para Selects já preparados, insere a quente os valores nos placeholders
     * @return \Ew\Db\Table\Result
     */       
    public function createNew(array $values = Array())
    {
        return new $this->_resultClassName($values, $this, $this->_defaultsToResult, true);
    }
    
    public function getAutoincrementFieldName()
    {
        return $this->_autoIncrement;
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function getSchemma()
    {
        return $this->_schemma;
    }
    
    public function getLabelOfTable()
    {
        return $this->_dataDic->getLabelOfTable($this->getName());
    }
    
    public function getLabel($field)
    {
        return $this->_dataDic->getLabel($field);
    }
    
    protected function _notFound(array $fieldList = Array(), $mensagem=null)
    {
        if($mensagem===null){
            $mensagem = "Não foi encontrado na tabela '[TABLE]' nenhum registro com [FIELDS]";
        }
        $fields = '';
        foreach($fieldList as $field => $value)
        {
            if($fields!=''){
                $fields.=', ';
            }
            $fields .= $this->_dataDic->getLabel($field) . " = '" . $value . "'";
        }
        $mensagem = str_replace('[TABLE]', $this->_dataDic->getLabelOfTable($this->_name), $mensagem);
        $mensagem = str_replace('[FIELDS]', $fields, $mensagem);
        
        \Ew\Error::critical($mensagem, 'Ew\Db\Table\NotFoundException');
    }
    
    protected function _notValid(array $fieldList = Array(), $mensagem=null)
    {
        \Ew\Error::notValid($this, \Ew\Helper\ArrayGetter::getArray($fieldList), $mensagem);
    }
}
