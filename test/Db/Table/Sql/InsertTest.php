<?php
namespace Ew\Db\Table\Sql;

include_once "testModels.php";
/**
 * @author nununo
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class InsertSqlTest extends \PHPUnit_Framework_TestCase 
{
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Insert('\test\TestTable');
    }
    
    public function testRender() {
        $insert = new Insert();
        $insert->into('\test\TestTable')
               ->setFieldsAndValues(Array('nome' => 'insertField', 'valor'=>666, 'filtro1'=>4 ));
        $this->assertEquals('INSERT INTO `testTable` (`nome`,`valor`,`filtro1`) VALUES (?,?,?)', $insert->toString());
    }
    
    public function testExecute()
    {
        $db = \Ew\Registry::get('db');
        $db->begin();
        
        $insert = new Insert();
        $insert->into('\test\TestTable')
               ->setFieldsAndValues(Array('nome' => 'insertField', 'valor'=>666, 'filtro1'=>51 ));
               
        $insert->execute();
        $select = new Select('\test\TestTable');
        $select->where('filtro1 = ?', 51);
        
        $result = $select->fetchAll();
        $this->assertEquals(1, count($result));
        $this->assertEquals(666, $result[0]['valor']);
        
        $insert->execute(Array('insertField2', 777, 52 ));
        
        $result = $select->fetchAll(Array(52));
        $this->assertEquals(1, count($result));
        $this->assertEquals(777, $result[0]['valor']);
        
        $db->rollback();
        
        $result = $select->fetchAll(Array(52));
        $this->assertEquals(0, count($result));
    }
    
    public function testExecuteError()
    {
        $db = \Ew\Registry::get('db');
        $db->begin();
        
        $insert = new Insert();
        $insert->into('nonExistTable')
               ->setFieldsAndValues(Array('nome' => 'insertField', 'valor'=>666, 'filtro1'=>51 ));
        
        try{
            $insert->execute();
            $this->assertEquals(1, 0, "Não foi lançada uma exceção");
        }catch(\Exception $e){
            $this->assertEquals(1, 1, "Foi lançada uma exceção");
        }
        
        $db->rollback();
    }
}