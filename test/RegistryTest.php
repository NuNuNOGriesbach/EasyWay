<?php

namespace Ew;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-12-09 at 19:03:11.
 */
class RegistryTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Registry
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Ew\Registry::set
     * @todo   Implement testSet().
     */
    public function testSet() {
        try{
            Registry::set('test','1');        
        }  catch (\Ew\Exception $e){
            $this->assertEmpty("erro", "O registro esta emitindo uma exceção ao tentar definir uma variavek ");
        }
    }

    /**
     * @covers Ew\Registry::get
     * @todo   Implement testGet().
     */
    public function testGet() {
        Registry::set('test','1');
        $this->assertEquals('1', Registry::get('test'), 'Um valor diferente do definido foi recebido');
        try{
           Registry::get('naoexiste');  
           $this->assertEmpty("erro", "Uma exceção deveria ter sido lançada");
        }  catch (\Ew\Exception $e){
            $this->assertEmpty("", "A exeção foi lançada");
        }
    }

    /**
     * @covers Ew\Registry::has
     * @todo   Implement testHas().
     */
    public function testHas() {
        
        $this->assertFalse(Registry::has('naoexiste'));
        Registry::set('existe','1');
        $this->assertTrue(Registry::has('existe'));
    }

}