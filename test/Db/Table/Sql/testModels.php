<?php
namespace test;

class TestDataDictionary extends \Ew\Db\Table\DataDictionary
{
    protected $_labelOfTable = "Tabela de testes";
    protected $_fieldMap = Array(
        'idtestTable' => Array('label'=>'Linha', 'type'=>'text'),
        
    );
    
}


class TestTable extends \Ew\Db\Table\TableAbstract
{
    protected $_schemma = null;
    protected $_name = 'testTable';
    protected $_defaultAlias = 'tt';

    protected $_primary = Array('idtestTable');
    protected $_autoIncrement = 'idtestTable';
    
    /**
     * Dicionário de dados
     * @var Ew\Db\Table\DataDicionary
     */
    protected $_dataDic = '\test\TestDataDictionary';
    
    /**
     * ponto de customização. os parametros podem ser invocados pela funcao initialize, ou na propriedade self::initParams
     * @param array $params
     */
    public function init($params = Array())
    {
        
        if(isset($params['autoRefresh'])){
            $this->_refreshOnUpdate = $params['autoRefresh'];
            $this->_refreshOnInsert = $params['autoRefresh'];
            $this->_refreshOnDelete = $params['autoRefresh'];
        }
        
        if(isset($params['refreshByPrimaryKey'])){
            $this->_refreshUseAutoIncrementKey = $params['refreshByPrimaryKey'];
        }
    }
    
    /**
     * 
     * @param type $filtro
     * @return \Ew\Db\Table\_resultClassName (\Ew\Db\Table\Result)
     */
    public function getByFiltro1($filtro)
    {
        $select = $this->select();
        $select->where('filtro1 = ?', $filtro);
        return $this->fetchAll($select);
    }
    
    /**
     * 
     * @param type $filtro
     * @return \Ew\Db\Table\_resultClassName (\Ew\Db\Table\Result)
     */
    public function getByFiltro1WhitNoFastInteract($filtro)
    {
        $select = $this->select();
        $select->where('filtro1 = ?', $filtro);
        
        //Define o modo de iteração por cópia
        $this->_defaultsToResult = Array('fastInteract' => false);
        $ret = $this->fetchAll($select);
        $this->_defaultsToResult = Array();
        return $ret;
    }
}


class TestExtendedResult extends \Ew\Db\Table\Result
{
    public function validateSave()
    {
        if($this->valor != $this->filtro1 . $this->filtro2){
            $this->_notValid('valor', "O valor precisa ser equivalente aos filtros");
        }
    }
    
    public function getValor()
    {
        return $this->valor;
    }
}

class TestTableWithCustomResult extends TestTable
{
    protected $_resultClassName = '\test\TestExtendedResult';
            
    /**
     * 
     * @param type $id
     * @return \test\TestExtendedResult
     */
    public function findById($id) {
        return parent::findById($id);
    }
}

class FiltroTable extends \Ew\Db\Table\TableAbstract
{
    protected $_schemma = null;
    protected $_name = 'Filtro';
    protected $_defaultAlias = 'f';

    protected $_primary = Array('idFiltro');
    protected $_autoIncrement = 'idFiltro';
}