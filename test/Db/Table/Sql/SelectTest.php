<?php
namespace Ew\Db\Table\Sql;

include_once "testModels.php";
/**
 * Description of SelectSqlTest
 *
 * @author nununo
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class SelectSqlTest extends \PHPUnit_Framework_TestCase 
{
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
       
        $this->object = new Select('\test\TestTable');
    }
    
    public function testFromRender() {
        $select = new Select('\test\TestTable');
        $select->from('\test\TestTable');
        $this->assertEquals('SELECT `tt`.* FROM `testTable` as `tt`', $select->toString());
        
        $select = new Select('\test\TestTable');        
        $this->assertEquals('SELECT `tt`.* FROM `testTable` as `tt`', $select->toString());
        
        $select = new Select();   
        $select->from('testTable');        
        $this->assertEquals('SELECT `testTable`.* FROM testTable as `testTable`', $select->toString());
        
        $select2 = new Select();
        $select2->from($select, 'nome,filtro1', 'sub');
        $this->assertEquals('SELECT `sub`.`nome`,`sub`.`filtro1` FROM (SELECT `testTable`.* FROM testTable as `testTable`) as sub', $select2->toString());
    }
    
    public function testJoinRender() {
        $select = new Select('\test\TestTable');
        $select->from('\test\TestTable');
        $select->join('\test\FiltroTable', 'nome  as filtro1Nome', 'f')
               ->on('tt.filtro1 = f.idFiltro')
               ->joinLeft('\test\FiltroTable', 'nome as  filtro2Nome', 'f2')
               ->on('tt.filtro2 = f2.idFiltro');
        
        $assert = $select->toString();
        
        $this->assertContains("FROM `testTable` as `tt`", $assert);
        $this->assertContains("INNER JOIN `Filtro` as `f`", $assert);
        $this->assertContains("LEFT JOIN `Filtro` as `f2`", $assert);
        $this->assertContains("`tt`.*,`f`.`nome` as `filtro1Nome`,`f2`.`nome` as `filtro2Nome`", $assert);
    }
    
    public function testWhereRender() {
        $select = new Select();
        $select->from('\test\TestTable');
        $select->join('\test\FiltroTable', 'nome  as filtro1Nome' ,'f')
               ->on('tt.filtro1 = f.idFiltro')
               ->joinLeft('\test\FiltroTable', 'nome as  filtro2Nome', 'f2')
               ->on('tt.filtro2 = f2.idFiltro')
               ->where('filtro1 = ?', 1)
               ->where('tt.nome = ?', "linha 1 1")
                ;
        
        $assert = $select->toString();
     
        $this->assertContains("FROM `testTable` as `tt`", $assert);
        $this->assertContains("INNER JOIN `Filtro` as `f`", $assert);
        $this->assertContains("LEFT JOIN `Filtro` as `f2`", $assert);
        $this->assertContains("`tt`.*,`f`.`nome` as `filtro1Nome`,`f2`.`nome` as `filtro2Nome`", $assert);
        
        $this->assertContains("WHERE", $assert);
        $this->assertContains("filtro1 = ?", $assert);
        $this->assertContains("tt.nome = ?", $assert);
    }
    
    public function testFetch() {
        $select = new Select();
        $select->from('\test\TestTable');
        $select->join('\test\FiltroTable', 'nome  as filtro1Nome' ,'f')
               ->on('tt.filtro1 = f.idFiltro')
               ->joinLeft('\test\FiltroTable', 'nome as  filtro2Nome', 'f2')
               ->on('tt.filtro2 = f2.idFiltro')
               ->where('filtro1 = ?', 1)
               ->where('filtro2 = ?', 1)
                ;
        
        $result = $select->fetchAll();
        
        $row = $result[0];
        $this->assertEquals('UM', $row['filtro1Nome']);
        $this->assertEquals('UM', $row['filtro2Nome']);
        
        $result = $select->fetchAll(Array('1', '2'));
        
        $row = $result[0];
        $this->assertEquals('UM', $row['filtro1Nome']);
        $this->assertEquals('DOIS', $row['filtro2Nome']);
        
        $select->reset('where')
                ->where('filtro1 = ?', 4)
                ->where('filtro2 = ?', 2);
        $result = $select->fetchAll();
        $row = $result[0];
        $this->assertEquals('QUATRO', $row['filtro1Nome']);
        $this->assertEquals('DOIS', $row['filtro2Nome']);
    }
    
    public function testFetchOperatorIn() {
        $select = new Select();
        $select->from('\test\TestTable');
        $select->join('\test\FiltroTable', 'nome  as filtro1Nome' ,'f')
               ->on('tt.filtro1 = f.idFiltro')
               ->joinLeft('\test\FiltroTable', 'nome as  filtro2Nome', 'f2')
               ->on('tt.filtro2 = f2.idFiltro')
               ->where('filtro2 in (?)', Array(1,2))               
                ;
       
        $result = $select->fetchAll();
                
        $row = $result[0];
        $this->assertContains($row['filtro2Nome'], Array('UM','DOIS'));
    }
    
    
    public function testFetchGroup() {
        $select = new Select();
        $select->from('\test\TestTable', Array())
                ->addCalculatedField('count(*)', 'total');
        $select->join('\test\FiltroTable', '' ,'f')
               ->on('tt.filtro1 = f.idFiltro')
               ->addCalculatedField('max(f.nome)', 'filtro1Nome')
               ->joinLeft('\test\FiltroTable', Array('nome as filtro2nome'), 'f2')
               ->on('tt.filtro2 = f2.idFiltro')
               ->group('f2.nome')
               ->order('f2.nome')   
               ->having('count(*) > ?', 1)
                ;
        
        $result = $select->fetchAll();
       
        $row = $result[0];
        $this->assertContains($row['filtro2nome'], Array('DOIS'));
    }
    
    public function testFetchOrder() {
        $select = new Select();
        $select->from('\test\TestTable', 'moneyField')
                ->order('moneyField', 'ASC', false);
                
        $result = $select->fetchAll();       
        $row = $result[0];
        $this->assertNull($row['moneyField']);
        
        $select = new Select();
        $select->from('\test\TestTable', 'moneyField')
                ->order('moneyField', 'ASC', true);
                
        $result = $select->fetchAll();       
        $row = $result[0];
        $this->assertNotNull($row['moneyField']);
    }
    
    public function testFetchLimit()
    {
        $select = new Select();
        $select->from('\test\TestTable', 'moneyField')
                ->order('moneyField', 'ASC', false)
                ->limit(1);
                
        $result = $select->fetchAll();       
        $row = $result[0];
        $this->assertNull($row['moneyField']);        
        $this->assertEquals(1, count($result));
        
        $select = new Select();
        $select->from('\test\TestTable', 'moneyField')
                ->order('moneyField', 'ASC', true)
                ->limit(1,3);
                
        $result = $select->fetchAll();       
        $row = $result[0];
        $this->assertNotNull($row['moneyField']);
        $this->assertEquals(3, count($result));
    }
    
    public function testVariable() 
    {
        $select = new Select();
        $select->setSqlBase("SELECT * from testTable where idTestTable = [ID]", true);
        $select->setVar('ID', 2);
        
        $result = $select->fetchAll();       
        
        $this->assertEquals(1, count($result));
    }
    
    public function testErrorFetch() 
    {
        $select = new Select();
        $select->setSqlBase("SELECT * from nonExistTable where id = [ID]", true);
        $select->setVar('ID', 2);
        
        try{
            $result = $select->fetchAll();
            $this->assertEquals(1, 0, 'Um throw deveria ter sido emitido');
        }catch (\Exception $e){
            $this->assertEquals(1, 1, 'Um throw foi emitido');
        }
        
        
    }
}
