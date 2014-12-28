<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ew\Form\Elements;

/**
 * Description of Container
 *
 * @author nununo
 */
abstract class Container extends Element{
    protected $_children = Array();
    protected $_subContainers = Array();
    protected $_autoManaged = Array();
    
    
    /**
     * Processa os dados recebidos pelo lado do cliente.
     * @param array $request
     * @return Container
     */
    public function processRequest(array &$request)
    {
        $this->processValues($request);
        return $this->processEvents($request);
    }    
    
    /**
     * Coloca os valores de campos enciados pelo clientSide em um lugar comum a todos os elementos para estarem disponíveis
     * no momento do getValue. Isto evita que um setValue seja chamado para cada elemento antes de processar cada evento,
     * tornando o mundo mais agil.
     * @param array $request
     * @return Container
     */
    public function processValues(array &$request){
        $this->_request = $request;
        if(!isset($request['values'])){
            return $this;
        }
        if(!is_array($request['values'])){
            $request['values'] = json_decode($request['values'], true);
        }
        static::$_values = $request['values'];
        
        foreach($this->_autoManaged as $autoManagedElement){
            $autoManagedElement->processValues($request);
        }
    }
    
    /**
     * Recebe um conjunto de parametros enviados pelo Client, ou usa os que recebeu na inicialização
     * para executar eventos disparados no clientSide pelo usuário. Deve ser preferencialmente chamado após
     * o processValues para que os eventos tenham acesso aos valores informados pelo usuário
     * @param array $request 
     * @return Container
     */
    public function processEvents(array &$request)
    {        
        $this->_request = $request;
        if(!isset($request['events'])){
            return $this;
        }
        if(!is_array($request['events'])){
            $request['events'] = json_decode($request['events'], true);
        }
        if(isset($request['events'][$this->getId()])){            
            foreach($request['events'][$this->getId()] as $eventName => $params){
               
                $method_name = $this->_getMethodByEvent($eventName);
                
                if(!$this->_executeEvent($method_name, $params)){
                    \Ew\Error::critical("o método '$method_name' não está declarado em {$this->getId()}");
                }                
            }
            //Apos executar, remove da fila de execução
            unset($request['events'][$this->getId()]);
        }
        
        if(empty($request['events'])){
            return $this;
        }
        
        foreach($this->_subContainers as $child){
            $child->processEvents($request); 
        }     
        
        if(!empty($request['events'])){
            $list = "";
            foreach($request['events'] as $containerName => $events){
                foreach($events as $method_name => $params){
                    $list .= $containerName . '::' . $method_name . ' não foi processado. ';
                }
            }
            //\Ew\Error::critical($list);
        }
        return $this;
    }
    
    /**
     * Recebe o alias de um evento do lado cliente e retorna o nome do metodo que trata este evento no servidor
     * @param string $eventName
     * @return string
     */
    protected function _getMethodByEvent($eventName)
    {
        if(!isset(static::$_eventDic[$eventName])){
            \Ew\Error::critical("O evento '$eventName' não está declarado em {$this->getId()}");
        }
        return static::$_eventDic[$eventName];
    }
    
    /**
     * Executa um metodo de evento, no container onde ele for encontrado. 
     * 
     * @param string $method_name
     * @param array $params
     * @return boolean
     */
    protected function _executeEvent($method_name, $params)
    {
        if(method_exists($this, $method_name)){
            $this->$method_name($params);
            return true;
        }
        if($this->_parent!==null){
            return $this->_parent->_executeEvent($method_name, $params);
        }
        return false;
    }
    
    public function add($child)
    {
        if(is_array($child)){
            return $this->addArray($child);
        }
        $this->_children[$child->getId()] = $child;
        if($child->isContainer()){
            $this->_subContainers[$child->getId()] = $child;
        }
        if($child->isAutoManaged()){
            $this->_autoManaged[$child->getId()] = $child;
        }
        $child->setParent($this);
        return $this;
    }
    
    public function addArray(array $children)
    {
        foreach($children as $child){
            $this->add($child);
        }
        return $this;
    }
    
    public function getChild($childId)
    {
        return $this->_children[$child->getId()];
    }
    
    public function getChilds()
    {
        return $this->_children;
    }
    
    public function render(Element $parent=null, array &$createCommands=Array())
    {
        parent::render($parent, $createCommands);
        foreach($this->_children as $child)
        {
            $child->render($this, $createCommands);
        }
        return $createCommands;
    }
    
    /**
     * 
     * @param type $id
     * @return \Ew\Form\Elements\Element
     */
    public function getElement($id)
    {
        if(isset($this->_children[$id])){
            return $this->_children[$id];
        }
        
        foreach($this->_subContainers as $container){
            $child = $container->getElement($id);
            if($child!==null){
                return $child;
            }
        }
        
        return null;
    }
    
    
    
    /**
     * Inicialização do mapeamento de eventos para os elementos do formulário
     * comunmente chamado após o init
     * @return \Ew\Form\Elements\Container
     */
    public function bindEvents()
    {
        Element::setSendBindedEventsToCliente();
        $this->initEvents();
        return $this;
    }
    
    public function isContainer()
    {
        return true;
    }
    
    public function isField()
    {
        return false;
    }
}
