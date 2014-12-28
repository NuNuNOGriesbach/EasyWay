<?php

namespace Ew\Db\Table\Sql;

/**
 * Description of Insert
 *
 * @author nununo
 */
class Delete extends \Ew\Db\Table\Sql\SqlAbstract
{
    protected $_baseName = 'delete';
   /**
     * Seções do SQL que geram erros caso sejam substituidas por ''
     * @var Array 
     */
    protected $_mandatoryReplaces = Array(
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
        'from' => Array(),        
        'where' => Array(),
        'limit' => Array(),
    );
    
    protected $_bindParts = Array(
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
    
        
    
    
}
