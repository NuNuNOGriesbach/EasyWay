<?php

namespace Ew\Form\Elements;

/**
 * Definições abstratas de elementos que podem conter, receber e enviar valores
 *
 * @author nununo
 */
class InputAbstract extends Element{
    protected $_value = null;
    protected $_default = null;
    
    /**
     * Define o valor do elemento
     * @param type $value
     * @return \Ew\Form\Elements\InputAbstract
     */
    public function setValue($value)
    {
        $this->_value = $value;
        static::$_values[$this->getId()]=$value;
        return $this;
    }
    
    /**
     * Recupera o último valor definido para o elemento. 
     * Se a aplicação recebeu um request, retornará o valor envido pelo clientSide
     * @param type $default valor retornado por padrão se o elemento estiver nulo.
     * @return type
     */
    public function getValue($default=null)
    {
        if($default!==null){
            $this->setDefault($default);
        }
        if($this->_value===null){
            if(isset(static::$_values[$this->getId()])){
                return static::$_values[$this->getId()];
            }
            return $this->_default;
        }
        return $this->_value;
    }
    
    /**
     * Define o valor padrão do elemento
     * @param type $value
     */
    public function setDefault($value){
        $this->_default = $value;
    }
}
