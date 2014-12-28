<?php
namespace Ew\Db\Table\Sql;

include_once "testModels.php";
/**
 * @author nununo
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class DeleteTest extends \PHPUnit_Framework_TestCase 
{
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        
        $this->object = new Delete('\test\TestTable');
    }
    
    public function testRender() {
        $delete = new Delete();
        $delete->into('\test\TestTable')
               ->where('idTestTable=?', 2);
        $this->assertEquals('DELETE FROM `testTable` WHERE (idTestTable=?)', $delete->toString());
    }
    
    public function testExecute()
    {
        $db = \Ew\Registry::get('db');
        $db->begin();
        
        $delete = new Delete();
        $delete->into('\test\TestTable')
               ->where('idTestTable=?', 2);
        
        $select = new Select('\test\TestTable');
        $select->where('idTestTable = ?', 2);        
        $result = $select->fetchAll();
        $this->assertEquals(1, count($result));
        
        $delete->execute();
        
        $select = new Select('\test\TestTable');
        $select->where('idTestTable = ?', 2);        
        $result = $select->fetchAll();
        $this->assertEquals(0, count($result));
                
        $db->rollback();
        
        $result = $select->fetchAll(Array(2));
        $this->assertEquals(1, count($result));
    }
    
    public function testExecuteError()
    {
        $db = \Ew\Registry::get('db');
        $db->begin();
        
        $delete = new Delete();
        $delete->into('nonExiste')
               ->where('idTestTable=?', 2);
        
        try{
            $delete->execute();
            $this->assertEquals(1, 0, "Não foi lançada uma exceção");
        }catch(\Exception $e){
            $this->assertEquals(1, 1, "Foi lançada uma exceção");
        }
        
        $delete = new Delete();
        $delete->into('\test\TestTable');
        
        try{
            $delete->execute();
            $this->assertEquals(1, 0, "Não foi lançada uma exceção");
        }catch(\Exception $e){
            $this->assertEquals(1, 1, "Foi lançada uma exceção");
        }
        
        $db->rollback();
    }
}