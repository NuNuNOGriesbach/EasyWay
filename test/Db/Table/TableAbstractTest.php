<?php

namespace Ew\Db\Table;

include_once "Sql/testModels.php";
/**
 * @author nununo
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class TableAbstractTest extends \PHPUnit_Framework_TestCase 
{
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        
        
    }
    
    public function testGet() 
    {
        $table = new \test\TestTable();
        $row = $table->getById(26);        
        $this->assertTrue($row->isEmpty());
        
        $row = $table->getById(2);        
        $this->assertFalse($row->isEmpty());
        
        $this->assertNotEmpty($row->nome);
        foreach($row as $it){
            $this->assertNotEmpty($it->nome);           
        }
        
    }
    
    public function testFind()
    {
        $table = new \test\TestTable();
        try{
            $row = $table->findById(26);
            $this->assertEquals(1, 0);
        }catch(\Ew\Db\Table\NotFoundException $e){
            $this->assertEquals(1, 1);
            
            //Valores configurados no dicionário de dados / testModels.php
            $this->assertContains('Tabela de testes', $e->getMessage());
            $this->assertContains('Linha', $e->getMessage());
        }
                
        $row = $table->findById(2);        
        $this->assertFalse($row->isEmpty());
        
        $this->assertNotEmpty($row->nome);
        foreach($row as $it){
            $this->assertNotEmpty($it->nome);           
        }
    }
    
    public function testUpdate()
    {
        $db = \Ew\Registry::get('db');
        $db->begin();
        
        $table = new \test\TestTable();
        $row = $table->findById(2);
        
        $row->nome = "alterado";
        $id = $row->save();
        $this->assertEquals(2,$id);
        
        $row = $table->findById(2);
        $this->assertEquals("alterado", $row->nome);
        $this->assertFalse($row->isNew());
        $this->assertFalse($row->isEmpty());
        
        $db->rollback();
    }
    
    public function testInsert()
    {
        $db = \Ew\Registry::get('db');
        $db->begin();
        
        $table = new \test\TestTable();
        $row = $table->createNew();
        
        $this->assertTrue($row->isNew());
        $this->assertTrue($row->isEmpty());
        
        $row->nome = "inserido";
        $newId = $row->save();
        $this->assertNotEmpty($newId);
        $this->assertEquals($newId, $row->getId());
        
        $row = $table->findById($newId);
        $this->assertEquals("inserido", $row->nome);
        $this->assertFalse($row->isNew());
        $this->assertFalse($row->isEmpty());
        
        $db->rollback();
    }
    
    public function testDelete()
    {
        $db = \Ew\Registry::get('db');
        $db->begin();
        
        $table = new \test\TestTable();
        $row = $table->findById(2);
        $row->delete();
        
        $row = $table->getById(2);
        $this->assertFalse($row->isNew());
        $this->assertTrue($row->isEmpty());
        
        $db->rollback();
    }
    
     /**
     * Utiliza uma classe derivada da TestTable padrão, contendo uma classe de Resutados 
     * customizada, com validações e funções próprias
     */
    public function testResultExtensions()
    {
        $db = \Ew\Registry::get('db');
        $db->begin();
        
        $table = new \test\TestTableWithCustomResult();
        $row = $table->findById(1); 
        $row->valor = 66; //Viola uma regra de validateSave()
        
        try{
            $row->save();
            $this->assertFalse(true);
        } catch (NotValidException $ex) {
            $this->assertTrue(true);            
            $this->assertContains('valor', $ex->fields);
        }
        
        $db->rollback();
    }
    
    public function testRefreshConfigs()
    {
        $db = \Ew\Registry::get('db');
        $db->begin();
        
        $tableRefresh = new \test\TestTableWithCustomResult(Array('autoRefresh'=>true));
        $tableNoRefresh = new \test\TestTableWithCustomResult(Array('autoRefresh'=>false));
        
        //Refresh após o inserr
        $refreshRow = $tableRefresh->createNew();
        $refreshRow->nome = "teste 55";
        $refreshRow->filtro1 = 5;
        $refreshRow->filtro2 = 5;
        $refreshRow->valor=55;
        
        $refreshRow->save();
        $this->assertNotEmpty($refreshRow->getId());
        $this->assertEquals('padrao', $refreshRow->defaultVal, "O campo com valor padrão, não assumiu o valor padrão");
        
        $nonRefreshRow = $tableNoRefresh->createNew();
        $nonRefreshRow->nome = "teste 66";
        $nonRefreshRow->filtro1 = 6;
        $nonRefreshRow->filtro2 = 6;
        $nonRefreshRow->valor=66;
        
        $this->assertEmpty($nonRefreshRow->getId(), 'Achou ' . $nonRefreshRow->getId());
        $nonRefreshRow->save();
        $this->assertEmpty($nonRefreshRow->getId(), 'Achou ' . $nonRefreshRow->getId());
        $this->assertEmpty($nonRefreshRow->defaultVal);
        
        $valorTruncado = "valor que certamente será truncado por conter";
        $valor45 = 'valor que certamente será truncado por conter mais de 45 digitos, que é o máximo para a coluna';
        
        //Refresh após o update e dados truncados        
        $refreshRow = $tableRefresh->findById(1);
        $refreshRow->nome = $valor45;
        $refreshRow->save();
        
        $this->assertEquals($valorTruncado, $refreshRow->nome);
        
        $nonRefreshRow = $tableNoRefresh->findById(1);
        $nonRefreshRow->nome = $valor45;
        $nonRefreshRow->save();
        
        $this->assertEquals($valor45, $nonRefreshRow->nome);
        
        //Atualiza manualmente
        $tableNoRefresh->refresh($nonRefreshRow);
        $this->assertEquals($valorTruncado, $nonRefreshRow->nome);
        
        $db->rollback();
    }
}
