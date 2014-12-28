<?php

namespace Ew\Db\Table;

/**
 * @author nununo
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class SqlFactoryTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var SqlFactory
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new SqlFactory();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    
    public function testGetSelect() {
        $select = $this->object->getSelect('testTable');
        $this->assertInstanceOf('\Ew\Db\Table\Sql\Select', $select);
    }
    
    public function testGetMagic() {
        $select = $this->object->factory('delete', 'testTable');
        $this->assertInstanceOf('\Ew\Db\Table\Sql\Delete', $select);
    }
    
    public function testConfig() {
        $this->object->setSqlType('novo', '\Ew\Db\Table\Sql\NONExisit');
        try{
            $select = $this->object->factory('novo', 'testTable');
            $this->assertEquals(1, 0, 'a exceção não foi gerada');
        }catch(\Exception $e){
            $this->assertEquals(1, 1);
        }
        
    }
    
}
