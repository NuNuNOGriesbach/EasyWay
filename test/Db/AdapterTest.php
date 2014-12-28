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
}