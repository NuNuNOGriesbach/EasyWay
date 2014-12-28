<?php
namespace Ew\Form\Elements;

/**
 * Description of TextField
 *
 * @author nununo
 */
class TextField extends InputAbstract{
    protected $_type = 'TextField';
    
    protected function _setDefaultsAttribs()
    {
        $this->_default('dataType', 'text');
    }
    
    /**
     * Define a largura do campo na tela, em pontos ou percentual. Força-la pode impedir que a linha se justifique corretamente
     * @param type $width
     * @return \Ew\Form\Elements\TextField
     */
    public function setWidth($width)
    {
        return $this->set('width', $width);
    }
    
    /**
     * retorna a largura do campo, em pontos ou percentagem. 
     * @return string
     */
    public function getWidth()
    {
        return $this->get('width');
    }
    
    /**
     * Define a largura máxima do campo na tela, em pontos ou percentual. Força-la pode impedir que a linha se justifique corretamente
     * @param type $width
     * @return \Ew\Form\Elements\TextField
     */
    public function setMaxWidth($width)
    {
        return $this->set('maxWidth', $width);
    }
    
    /**
     * retorna a largura máxima do campo na tela, em pontos ou percentual.
     * @return string
     */
    public function getMaxWidth()
    {
        return $this->get('maxWidth');
    }
    
    /**
     * Define o título do campo
     * @param string $value
     * @return \Ew\Form\Elements\TextField
     */
    public function setLabel($value)
    {
        return $this->set('label', $value);
    }
    
    /**
     * retorna título do campo
     * @return string
     */
    public function getLabel()
    {
        return $this->get('label');
    }
    
    /**
     * Define valor padrão do campo
     * @param string $value
     * @return \Ew\Form\Elements\TextField
     */
    public function setDefaultValue($value)
    {
        return $this->set('value', $value);
    }
    
    /**
     * retorna valor padrão do campo
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->get('value');
    }
    
    /**
     * Define o número máximo de digitos que o elemento pode conter
     * @param integer $value
     * @return \Ew\Form\Elements\TextField
     */
    public function setSize($value)
    {
        return $this->set('size', $value);
    }
    
    /**
     * retorna o número máximo de digitos que o elemento pode conter
     * @return integer
     */
    public function getSize()
    {
        return $this->get('size');
    }
    
    /**
     * Define que o alinhamento ultimo ponto a direita do elemento será igual ao de outro campo. 
     * @param string $field
     * @return \Ew\Form\Elements\TextField
     */
    public function setSameRight($field)
    {
        return $this->set('sameRight', $field);
    }
    
    /**
     * retorna valor padrão do campo
     * @return string
     */
    public function getSameRight()
    {
        return $this->get('sameRight');
    }
    
    /**
     * Define o tipo de dados que são exibidos e entrados por este campo
     * 'text' => texto puro
     * 'upperText' => texto em caixa alta
     * 'lowerText' => texto em caixa baixa
     * 'url' => específico para links
     * 'email' => especifico para emails
     * 'integer' => apenas números
     * 'number' => número livre, pode-se informar número de casas decimais
     * 'date' => datas
     * 'datetime' => data e hora
     * @param string $dataType
     * @return \Ew\Form\Elements\TextField
     */
    public function setDataType($dataType, $decimals=null)
    {
        if($dataType=='number'){
            if($decimals!==null && $decimals*1 > 0){
                $this->setDecimals($decimals * 1);
            }            
        }
        return $this->set('dataType', $dataType);
    }
    
    /**
     * retorna o tipo de dados que são exibidos e entrados por este campo
     * @return string
     */
    public function getDataType()
    {
        return $this->get('dataType');
    }
    
    public function setDecimals($decimals)
    {
        return $this->set('decimals', $decimals);
    }
    
    public function getDecimals()
    {
        return $this->get('decimals');
    }
}
