<?php

namespace Ew\Db\Table\Sql;

/**
 * Description of Insert
 *
 * @author nununo
 */
class Update extends \Ew\Db\Table\Sql\SqlAbstract
{
    protected $_baseName = 'update';
   /**
     * Seções do SQL que geram erros caso sejam substituidas por ''
     * @var Array 
     */
    protected $_mandatoryReplaces = Array(
        'SET_PART' => 'renderSet',
        'WHERE_PART' => 'renderWhere',
        'FROM_PART'  => 'renderInto',
    );
    
    /**
     * Seções do SQL que não geram erros caso sejam substituidas por ''
     * @var Array 
     */
    protected $_optionalReplaces = Array(       
        'LIMIT_PART'  => 'renderLimit',
    );
    
    
    protected $_renderAsSubQuery = false;
    
    protected $_alias = '';
    
    protected $_parts = Array(
        'fields' => Array(),
        'from' => Array(),        
        'values' => Array(),   
        'where' => Array(),
        'limit' => Array(),
    );
    
    protected $_bindParts = Array(
        'fields' => Array(),
        'where' => Array(),
    );
    protected $_bindValues = Array();
    
    /**
     * 
     * @param type $table
     * @return \Ew\Db\Table\Sql\SqlAbstract\Insert
     */
    public function into($table)
    {
        return parent::from($table, Array());
    }
    
    public function from($table)
    {
        return parent::from($table, Array());
    }
    
    public function setFieldsAndValues(array $values = Array())
    {
        $this->_parts['fields']=Array();
        $this->_bindParts['fields']=Array();
        foreach($values as $field => $value){
            $this->_parts['fields'][] = "`$field`";
            $this->_bindParts['fields'][] = $value;
        }
        return $this;
    }
    
        
}
