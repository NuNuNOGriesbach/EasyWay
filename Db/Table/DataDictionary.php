<?php
namespace Ew\Db\Table;

use \Ew\Form\Elements\Element;
use \Ew\Form\Elements\Form;
use \Ew\Form\Elements\Line;
use \Ew\Form\Elements\Group;
use \Ew\Form\Elements\Pager;
use \Ew\Form\Elements\Page;
use \Ew\Form\Elements\TextField;

/**
 * Define uma forma programática de descrever o dicionário de dados
 * -> significados dos campos
 * -> representação visual dos campos
 * -> validações padrão dos campos
 * -> Relações dos campos com outras tabelas
 * -> Nomes amigáveis para o usuário
 *
 * @author nununo
 */
class DataDictionary {
    const FIELD_MAP = 'fieldMap';
    const PREFIX = 'prefix';
    const TABLE = 'table';
    
    /**
     * Nome amigável da tabela para insersão em mensagens de erro e titulos de formulários
     * @var string 
     */
    protected $_labelOfTable = null;
    
    protected $_prefix = '';
    protected $_fieldMap = Array();
    
    /**
     *
     * @var \Ew\Db\Table\TableAbstract
     */
    protected $table = null;
    
    protected $_elementsNamespaces = Array("\\Ew\\Form\\Elements\\");
    
    public function __construct($params = Array())
    {
        if(isset($params[self::FIELD_MAP])){
            $this->setFieldMap($params[self::FIELD_MAP]);
        }
        
        if(isset($params[self::PREFIX])){
            $this->setPrefix($params[self::PREFIX]);
        }
        
        $this->init($params);
    }
    
    /**
     * Ponto de customização para dicionários personalizados
     */
    public function init()
    {
        
    }
    
    public function getLabelOfTable($or)
    {
        if($this->_labelOfTable===null || empty($this->_labelOfTable)){
            return $or;
        }
        return $this->_labelOfTable;
    }
    
    /**
     * Define o mapa de campos
     * @param Array $fieldMap
     * @return \Ew\Model\DataDicionary
     */
    public function setFieldMap(array $fieldMap)
    {
        $this->_fieldMap = $fieldMap;
        return $this;
    }
    
    /**
     * retorna o mapa de campos atual
     * @return array
     */
    public function getFieldMap()
    {
        return $this->_fieldMap;
    }
    
    /**
     * Define o prefixo que será utilizado para a criação do nome de elementos
     * @param type $prefix
     * @return \Ew\Model\DataDicionary
     */
    public function setPrefix($prefix)
    {
        $this->_prefix = $prefix;
        return $this;
    }
    
    /**
     * Retorna o prefixo utilizado para a criação do nome de elementos
     * @param type $prefix
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }
    
    /**
     * Adiciona ou modifica um atributo do campo
     * @param string $fieldName
     * @param string $attrib
     * @param string $value
     * @return \Ew\Model\DataDicionary
     */
    public function setField($fieldName, $attrib, $value)
    {
        $this->_fieldMap[$fieldName][$attrib] = $value;
        return $this;
    }
    
    /**
     * Adiciona um campo, sobrescrevendo se já existir
     * @param type $fieldName
     * @param array $attribs
     * @return \Ew\Model\DataDicionary
     */
    public function addField($fieldName, array $attribs=Array())
    {
        $this->_fieldMap[$fieldName] = $attribs;
        return $this;
    }
    
    /**
     * Adiciona atributos a um campo, sobrescrevendo os que já existam
     * @param type $fieldName
     * @param array $attribs
     * @return \Ew\Model\DataDicionary
     */
    public function field($fieldName, array $attribs=Array())
    {
        foreach($attribs as $attrib => $value){
            $this->setField($fieldName, $attrib, $value);
        }
        
        return $this;
    }
    
    public function getFieldAttribute($fieldName, $attrib, $or=null)
    {
        if(!isset($this->_fieldMap[$fieldName][$attrib])){
            return $or;
        }
        return $this->_fieldMap[$fieldName][$attrib];
    }
    
    public function setType($fieldName, $value)
    {
        return $this->setField($fieldName, 'type', $value);
    }
    
    public function getType($fieldName)
    {
        return $this->getFieldAttribute($fieldName, 'type');
    }
    
    public function setDataType($fieldName, $value)
    {
        return $this->setField($fieldName, 'dataType', $value);
    }
    
    public function getDataType($fieldName)
    {
        return $this->getFieldAttribute($fieldName, 'dataType');
    }
    
    public function setDecimals($fieldName, $value)
    {
        return $this->setField($fieldName, 'decimals', $value);
    }
    
    public function getDecimals($fieldName)
    {
        return $this->getFieldAttribute($fieldName, 'decimals');
    }
    
    public function setLabel($fieldName, $value)
    {
        return $this->setField($fieldName, 'label', $value);
    }
    
    public function getLabel($fieldName, $or=null)
    {
        if($or === null){
            $or = $fieldName;
        }
        return $this->getFieldAttribute($fieldName, 'label', $or);
    }
    
    public function setHelp($fieldName, $value)
    {
        return $this->setField($fieldName, 'help', $value);
    }
    
    public function getHelp($fieldName)
    {
        return $this->getFieldAttribute($fieldName, 'help');
    }
    
    public function setWidth($fieldName, $value)
    {
        return $this->setField($fieldName, 'width', $value);
    }
    
    public function getWidth($fieldName)
    {
        return $this->getFieldAttribute($fieldName, 'width');
    }
    
    public function setMaxWidth($fieldName, $value)
    {
        return $this->setField($fieldName, 'maxWidth', $value);
    }
    
    public function getMaxWidth($fieldName)
    {
        return $this->getFieldAttribute($fieldName, 'maxWidth');
    }
    
    public function setDefaultValue($fieldName, $value)
    {
        return $this->setField($fieldName, 'value', $value);
    }
    
    public function getDefaultValue($fieldName)
    {
        return $this->getFieldAttribute($fieldName, 'value');
    }
    
    public function setSize($fieldName, $value)
    {
        return $this->setField($fieldName, 'size', $value);
    }
    
    public function getSize($fieldName)
    {
        return $this->getFieldAttribute($fieldName, 'size');
    }
    
    public function setSameRight($fieldName, $value)
    {
        return $this->setField($fieldName, 'sameRight', $value);
    }
    
    public function getSameRight($fieldName)
    {
        return $this->getFieldAttribute($fieldName, 'sameRight');
    }
    
    public function getFieldInstance($fieldName, $attribs = Array())
    {
        $this->field($fieldName, $attribs);
        $className = $this->getFieldAttribute($fieldName, 'type');
        
        $attribs = $this->_fieldMap[$fieldName];
        $attribs['id'] = $this->_prefix . $fieldName;
        
        if(!isset($attribs['name'])){
            $attribs['name'] =  $fieldName;
        }
        
        if(class_exists($className)){
            $obj = new $className($attribs);
            return $obj;
        }
        
        foreach($this->_elementsNamespaces as $namespace){
            $fullClassName = $namespace . $className;
            
            if(class_exists($fullClassName)){
                $obj = new $fullClassName($attribs);
                return $obj;
            }
            
        }
        
        throw new \Exception("$className não está definida");
    }
    
    public function getFieldInstances(array $fieldNames)
    {
        $result = Array();
        foreach($fieldNames as $fieldName){
            if(is_array($fieldName)){
                $result += $this->getFieldInstances($fieldName);
            }elseif(is_object($fieldName) && $fieldName instanceof Element){
                $result[$fieldName->getId()] = $fieldName;
            }else{
                $result[$fieldName] = $this->getFieldInstance($fieldName);
            }
        }
        return $result;
    }
    
    public function printInitCode($tabs="    ", $eol="\n", $pre=true, $die = false)
    {
        if($pre){
            echo "<pre>";
        }
        echo "$tabs" . "protected " . '$' . "_fieldMap = Array($eol";
        
        foreach($this->fieldMap as $field => $attribs){
            echo "$tabs$tabs'" . $field . "' => Array($eol";
            
            foreach($attribs as $attrib => $value){
                echo "$tabs$tabs$tabs'" . $attrib . "' => '$value',$eol";
            }
            echo "$tabs$tabs),$eol";
        }
        
        echo "$tabs);";
        if($pre){
            echo "</pre>";
        }
        if($die){
            die("//---");
        }
    }
    
    
    
}
