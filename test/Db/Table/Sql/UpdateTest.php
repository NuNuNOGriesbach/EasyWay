<?php
namespace Ew\Db\Table\Sql;

include_once "testModels.php";
/**
 * @author nununo
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class UpdateTest extends \PHPUnit_Framework_TestCase 
{
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        
        $this->object = new Update('\test\TestTable');
    }
    
    public function testRender() {
        $update = new Update();
        $update->from('\test\TestTable')
               ->setFieldsAndValues(Array('nome' => 'insertField', 'valor'=>666, 'filtro1'=>4 ))
               ->where('idTestTable=?',1);
        $this->assertEquals('UPDATE `testTable` SET `nome`=?,`valor`=?,`filtro1`=? WHERE (idTestTable=?)', $update->toString());
    }
    
    public function testExecute()
    {
        $db = \Ew\Registry::get('db');
        $db->begin();
        
        $update = new Update();
        $update->from('\test\TestTable')
               ->setFieldsAndValues(Array('nome' => 'updateField', 'valor'=>666, 'filtro1'=>51 ))
               ->where('idTestTable=?',1);
               
        $update->execute();
        $select = new Select('\test\TestTable');
        $select->where('filtro1 = ?', 51);
        
        $result = $select->fetchAll();
        $this->assertEquals(1, count($result));
        $this->assertEquals(666, $result[0]['valor']);
        
        $update->execute(Array('updateField2', 777, 52, 2 ));
        
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
        
        $update = new Update();
        $update->into('nonExistTable')
               ->setFieldsAndValues(Array('nome' => 'errorField', 'valor'=>666, 'filtro1'=>51 ))
               ->where('idTestTable=?',1);
        
        try{
            $update->execute();
            $this->assertEquals(1, 0, "Não foi lançada uma exceção");
        }catch(\Exception $e){
            $this->assertEquals(1, 1, "Foi lançada uma exceção");
        }
        
        $update = new Update();
        $update->from('\test\TestTable')
               ->setFieldsAndValues(Array('nome' => 'updateField', 'valor'=>666, 'filtro1'=>51 ));
               
        try{
            $update->execute();
            $this->assertEquals(1, 0, "Não foi lançada uma exceção por estar sem WHERE");
        }catch(\Exception $e){
            $this->assertEquals(1, 1, "Foi lançada uma exceção");
        }
        
        try{
            $update->execute(Array(1,2,3));
            $this->assertEquals(1, 0, "Não foi lançada uma exceção por estar sem WHERE");
        }catch(\Exception $e){
            $this->assertEquals(1, 1, "Foi lançada uma exceção");
        }
        
        $db->rollback();
    }
}