<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Element
 *
 * @author nununo
 */
namespace Ew\Form\Elements;

abstract class Element {
    protected $_type = 'Element';
    
    protected $_attribs;
    protected $_name = null;
    protected $_id = null;
    /**
     *
     * @var \Ew\Form\Elements\Container 
     */
    protected $_parent = null;
    
    protected $_elementNumber;
    protected static $_count = Array(); //ElementType => NumberOfElementTypeInstances
    protected static $_ids = Array();     // id => id
    protected static $_values = Array(); // fieldName => value
    protected static $_eventDic = Array(); // eventName => methodName
    
    
    /**
     * Indica quando o comando Event será emitido para o cliente, normalmente só ocorre em tempo de desenho (index)
     * @var boolean 
     */
    protected static $_sendBindedEventsToClient = false;
    
    public function __construct($attribs, $request=null)
    {
        if(is_array($attribs)){
            $this->_attribs = $attribs;
        }elseif($attribs!==null && !empty($attribs)){
            $this->setName($attribs)->setId($attribs);
        }
        $this->_registerInstanceNumber();
        $this->_registerName();
        $this->_setType();
        $this->_setDefaultsAttribs();
        
        $this->init($attribs);
    }
    
    
    public function setSendBindedEventsToCliente($send=true)
    {
        static::$_sendBindedEventsToClient = $send;
    }
    
    /**
     * Rotinas de inicialização onde os elementos devem ser criadas. É chamada em todos os requests.
     * @param type $attribs
     */
    public function init($attribs)
    {
        
    }
    
    /**
     * Rotinas customizadas de mapeamento de eventos, chamado por bindEvents do continer principal.
     */
    public function initEvents()
    {
        
    }
    
    protected function _setDefaultsAttribs()
    {
        
    }
    
    protected function _default($attrib, $value)
    {
        if(!isset($this->_attribs[$attrib])){
            $this->_attribs[$attrib] = $value;
        }
    }
    
    /**
     * Define um atributo do elemento
     * @param strign $attrib nome do atributo
     * @param string $value valor do atributo
     * @return \Ew\Form\Elements\Element
     */
    public function set($attrib, $value)
    {
        $this->_attribs[$attrib] = $value;
        return $this;
    }
    
    /**
     * Retorna o valor de um atributo
     * @param type $attrib
     * @return null|string
     */
    public function get($attrib)
    {
        if (isset($this->_attribs[$attrib])){
            return $this->_attribs[$attrib];
        }
        return null;
    }
    
    protected function _setType()
    {
        $this->_attribs['type'] = $this->_type;
    }
    
    protected function _registerInstanceNumber()
    {
        if(!isset(static::$_count[$this->_type])){
            static::$_count[$this->_type] = 0;
        }
        static::$_count[$this->_type] ++;
        $this->_elementNumber = static::$_count[$this->_type];
    }
    
    protected function _registerName()
    {
        if($this->_name===null){
            if(isset($this->_attribs['name'])){
                $this->_name = $this->_attribs['name'];
            }
        }
    }
    
    public function setName($name)
    {
        $this->_name = $name;
        $this->_attribs['name'] = $name;
        return $this;
    }
    
    public function getName()
    {
        if($this->_name === null){
            if(isset($this->_attribs['name'])){
                return $this->_attribs['name'];
            }
            $this->setName($this->getId());
        }
        return $this->_name;
    }
    
    public function setId($id)
    {
        if($this->_id!==null){
            unset(static::$_ids[$this->_id]);
        }
        if(isset(static::$_ids[$id])){
            \Ew\Error::critical("$id já esta definido");
        }
        $this->_id = $id;
        static::$_ids[$id] = true;
        return $this;
    }
    
    public function getId()
    {
        if($this->_id===null){
            if(isset($this->_attribs['id'])){
                $this->setId($this->_attribs['id']);
            }else{
                $this->setId($this->_type . $this->_elementNumber);
            }
        }
        return $this->_id;
    }
    
    public function setParent(Element $parent)
    {
        $this->_parent = $parent;        
        return $this;
    }
    
    public function getParent()
    {
        return $this->_parent;
    }
    
    public function render(Element $parent=null, array &$createCommands=Array())
    {
        $id = $this->getId();
        $createCommands[$id] = $this->_attribs;
        $createCommands[$id]['name'] = $this->getName();
        $createCommands[$id]['type'] = $this->_type;
        
        if($parent!==null){
            $createCommands[$id]['parent'] = $parent->getId();
        }elseif($this->_parent!==null){
            $createCommands[$id]['parent'] = $this->_parent->getId();
        }
                
        return $createCommands;
    }
    
    public function bindServerEvent($clientEventName, $methodName, $sendValues='')
    {
        $alias = $this->getId() . $clientEventName;
        static::$_eventDic[$alias] = $methodName;
        if(static::$_sendBindedEventsToClient){
            $event = Array('element'=>$this->getId(), 'parent'=>$this->_parent->getId(), 'event'=>$clientEventName, 'do'=>'sender.triggerEvent', 'server'=>$alias , 'send'=>$sendValues);
            \Ew\Command::bindServerEvent($event);
        }
    }
    
    public function isContainer()
    {
        return false;
    }
    
    public function isField()
    {
        return true;
    }
    
    public function isAutoManaged()
    {
        return false;
    }
    
   
}
