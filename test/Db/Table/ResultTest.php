<?php

namespace Ew\Db\Table;

include_once "Sql/testModels.php";
/**
 * @author nununo
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class ResultTest extends \PHPUnit_Framework_TestCase 
{
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        
        
    }
    
    public function testClassicIteration() 
    {
        $table = new \test\TestTable();
        $rowset = $table->getByFiltro1(1);       
        $this->assertFalse($rowset->isEmpty());
        $this->assertGreaterThan(0, $rowset->idtestTable);
        
        $lastId = $rowset->idtestTable;
        $count=0;
        foreach($rowset as $row){
            if($count==0){
                $this->assertEquals($lastId, $row->idtestTable, "Na iteração $count");
            }else{
                $this->assertNotEquals($lastId, $row->idtestTable, "Na iteração $count");
            }  
            $lastId = $row->idtestTable;
            $count++;
        }
        
    }
    
    public function testUnusualIteration() 
    {
        $table = new \test\TestTable();
        $rowset = $table->getByFiltro1(1);       
        $this->assertFalse($rowset->isEmpty());
        $this->assertGreaterThan(0, $rowset->idtestTable);
        
        $lastRow = $rowset->current();
        $count=0;
        foreach($rowset as $row){
            if($count==0){
                $this->assertEquals($lastRow->idtestTable, $row->idtestTable, "Na iteração $count");
            }else{
                $this->assertNotEquals($lastRow->idtestTable, $row->idtestTable, "Na iteração $count");
            }  
            $lastRow = $row->copy();
            $count++;
        }
        
    }
    
    public function testUnusualIterationWhitNonFastIteract() 
    {
        $table = new \test\TestTable();
        $rowset = $table->getByFiltro1WhitNoFastInteract(1);       
        $this->assertFalse($rowset->isEmpty());
        $this->assertGreaterThan(0, $rowset->idtestTable);
        
        $lastRow = $rowset->current();
        $count=0;
        foreach($rowset as $row){
            if($count==0){
                $this->assertEquals($lastRow->idtestTable, $row->idtestTable, "Na iteração $count");
            }else{
                $this->assertNotEquals($lastRow->idtestTable, $row->idtestTable, "Na iteração $count");
            }  
            //Diferente do outro teste, aqui a copia é feita automaticamente
            $lastRow = $row;
            $count++;
        }
        
    }
    
    /**
     * Utiliza uma classe derivada da TestTable padrão, contendo uma classe de Resutados 
     * customizada, com validações e funções próprias
     */
    public function testExtensions()
    {
        $table = new \test\TestTableWithCustomResult();
        $rowset = $table->findById(1); 
        $this->assertInstanceOf('\test\TestExtendedResult', $rowset);
        $this->assertNotEmpty($rowset->getValor());
    }
    
    
    
    
}
