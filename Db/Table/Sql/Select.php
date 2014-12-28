<?php

namespace Ew\Db\Table\Sql;

/**
 * Description of Select
 *
 * @author nununo
 */
class Select extends \Ew\Db\Table\Sql\SqlAbstract
{
    protected $_baseName = 'select';
    
    /**
     * Seções do SQL que geram erros caso sejam substituidas por ''
     * @var Array 
     */
    protected $_mandatoryReplaces = Array(
        'FIELD_PART' => 'renderField',
        'FROM_PART' => 'renderFrom',         
    );
    
    /**
     * Seções do SQL que não geram erros caso sejam substituidas por ''
     * @var Array 
     */
    protected $_optionalReplaces = Array(       
        'JOIN_PART' => 'renderJoin', 
        'GROUP_PART' => 'renderGroup', 
        'WHERE_PART' => 'renderWhere',         
        'HAVING_PART' => 'renderHaving', 
        'ORDER_PART' => 'renderOrder', 
        'LIMIT_PART' => 'renderLimit',
    );
    
    
    protected $_renderAsSubQuery = false;
    
    protected $_alias = '';
    
    protected $_parts = Array(
        'fields' => Array(),
        'from' => Array(),        
        'join' => Array(),
        'group' => Array(),
        'where' => Array(),        
        'having' => Array(),
        'order' => Array(),
        'limit' => Array(),        
    );
    
    protected $_bindParts = Array(
        'where' => Array(),
        'having' => Array(),
    );
    protected $_bindValues = Array();
    
    
            
    public function setAlias($alias)
    {
        if($alias==''){
            \Ew\Error::critical("O alias não pode ser em branco");
        }
        $this->_alias = $alias;
        $this->_renderAsSubQuery = true;
    }
    
    public function getAlias()
    {
        return $this->_alias;
    }
    
    public function fields($tableName, $fields='*'){
        
        if(!is_array($fields)){
            $fields = explode(',', $fields);
        }
        foreach($fields as $field){
            $this->addField($tableName, $field);
        }    
        return $this;
    }
    
    public function addField($tableName, $field)
    {
        if(empty($field)){
            return $this;
        }
        
        if($field!='*'){
            $field = "`$field`";
        }
        if(strpos($field, ' as ')){
            $tmp = explode(' as ', $field);
            $field = trim($tmp[0]) . '` as `' . trim($tmp[1]);
        }
        
        $this->_parts['fields'][] = "`$tableName`.$field";
        return $this;
    }
    
    public function addCalculatedField($field, $alias)
    {
        $this->_parts['fields'][] = "$field as $alias";
        return $this;
    }
    
    public function group($field)
    {
        $this->_parts['group'][] = $field;
        return $this;
    }
    
    public function order($field, $direction = 'ASC', $nullsLast = true)
    {
        $this->_parts['order'][] = Array('field' => $field, 'direction' => $direction, 'nullsLast' => $nullsLast );
        return $this;
    }
    
    public function join($table, $fields='*', $alias='', $type='INNER')
    {
        $table = $this->_getTableObject($table);
        if(is_object($table)){
            if(method_exists($table, 'getPath')){
                $alias = $table::getAlias($alias);
                if(isset($this->_parts['join'][$alias])){
                    \Ew\Error::critical("O alias '$alias' já está definido");
                }
                $this->_parts['join'][$alias] = Array('type' => $type, 'render' => new AliasRender($table->getPath(), $alias), 'on'=>Array());           
            }elseif($table instanceof \Ew\Db\Table\Sql\Select){
                $table->setAlias($alias);
                $this->_parts['join'][$alias] = Array('type' => $type, 'render' => $table, 'on'=>Array());
                $alias = $table->getAlias();
            }else{
                \Ew\Error::critical(get_class($table) . ' não é um objeto valido para uma tabela sql');
            }
        }else{
            $render = new AliasRender($table, $alias);
            $this->_parts['join'][$alias] = Array('type' => $type, 'render' => $render, 'on'=>Array());
            $alias = $render->getAlias();
        }
        
        $this->setActiveJoin($alias);
        return $this->fields($alias, $fields);
    }
    
    public function joinLeft($table, $fields='*', $alias='')
    {
        return $this->join($table, $fields, $alias, 'LEFT');
    }
    
    public function setActiveJoin($alias)
    {
        $this->_activeJoin = $alias;
        return $this;
    }
    
    public function on($exp)
    {
        $this->_parts['join'][$this->_activeJoin]['on'][] = $exp;        
        return $this;
    }
    
    public function toString()
    {
        if(empty($this->_parts['from'])){
            $this->from($this->_table);
        }
        return parent::toString();
    }
    
    public function render()
    {
        if($this->_renderAsSubQuery){
            return '(' . $this->toString() . ') as ' . $this->getAlias();
        }
        return $this->toString();
    }
}
