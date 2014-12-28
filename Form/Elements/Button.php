<?php
namespace Ew\Form\Elements;

/**
 * Description of Button
 *
 * @author nununo
 */
class Button extends TextField{
    protected $_type = 'Button';
    protected $_onClick = 'click';
    
    public function setOnClick($function)
    {
        if(is_array($function)){
            if(!is_object($function[0])){
                Error::critical("Evento para o botão {$this->_name} foi configurado incorretamente");
            }
        }
    }
   
    
    public function isField()
    {
        return false;
    }
    
    /**
     * Define o caption do botão
     * @param string $value
     * @return \Ew\Form\Elements\Button
     */
    public function setLabel($value)
    {
        return $this->set('value', $value);
    }
    
    /**
     * retorna o caption do botão
     * @return string
     */
    public function getLabel()
    {
        return $this->get('value');
    }
    
    /**
     * Define o caption do botão
     * @param string $value
     * @return \Ew\Form\Elements\Button
     */
    public function setValue($value)
    {
        return $this->set('value', $value);
    }
    
    /**
     * retorna o caption do botão
     * @return string
     */
    public function getValue()
    {
        return $this->get('value');
    }
    
    /**
     * Evento chamado quando o botão for do tipo server;
     */
    public function click()
    {
        
    }
}
