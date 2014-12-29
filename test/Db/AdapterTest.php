<?php

namespace Ew\Db;

include_once "Db/Table/Sql/testModels.php";
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
    
    public function testGetColumns()
    {
        $table = new \test\TestTable();
        
        $db = \Ew\Registry::get('db');
        $struct = $db->getColumns($table);
        
        foreach($struct as $field =>$info)
        {
            $this->assertArrayHasKey('name', $info);
            $this->assertArrayHasKey('type', $info);
            $this->assertArrayHasKey('kind', $info);
            $this->assertArrayHasKey('size', $info);
            $this->assertArrayHasKey('precision', $info);
            $this->assertArrayHasKey('required', $info);
            $this->assertArrayHasKey('primary', $info);
            $this->assertArrayHasKey('unique', $info);
            $this->assertArrayHasKey('indexed', $info);
            $this->assertArrayHasKey('auto_increment', $info);
            $this->assertEquals($field, $info['name']);
                                    
            //Valores permitidos para kind, que serão usados na montagem dinamica do dicionário de dados.
            $this->assertContains($info['kind'], Array('number','text','textarea','boolean','date','datetime'));
            
        }
    }
    
    public function testGetIndexes()
    {
        $table = new \test\TestTable();
        
        $db = \Ew\Registry::get('db');
        $struct = $db->getIndexes($table);
        $this->assertEquals(5, count($struct));
        
        foreach($struct as $idxName =>$info)
        {
            $this->assertArrayHasKey('name', $info);
            $this->assertArrayHasKey('primary', $info);
            $this->assertArrayHasKey('unique', $info);
            $this->assertArrayHasKey('auto_increment', $info);
            $this->assertArrayHasKey('fields', $info);
            $this->assertEquals($idxName, $info['name']);
            
            foreach($info['fields'] as $fieldName => $fieldInfo){
                $this->assertArrayHasKey('seq', $fieldInfo);
                $this->assertArrayHasKey('name', $fieldInfo);
                $this->assertArrayHasKey('can_be_null', $fieldInfo);
                $this->assertEquals($fieldName, $fieldInfo['name']);
            }
                                     
        }
    }
    
    public function testGetTables(){
        $db = \Ew\Registry::get('db');
        $tables = $db->getTables();
        foreach($tables as $tableName => $info){
            $this->assertArrayHasKey('name', $info);
            $this->assertArrayHasKey('schemma', $info);
            $part = explode('.', $tableName);
            $this->assertEquals($part[0], $info['schemma']);
            $this->assertEquals($part[1], $info['name']);
        }
    }
}