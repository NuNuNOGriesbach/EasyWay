<?php

namespace Ew;

/**
 * Description of Error
 *
 * @author nununo
 */
class Error {
    static public $logFileName = 'Error.log'; 
    
    static public function handler(\Ew\Exception $e){
        static::_process($e);
        
        if($e->isCritical){
            throw $e;
        }
    }
    
    static protected function _process($e)
    {
        if($e->isLog){
            static::log($e);
        }
        
        Command::error($e);
    }
    
    static public function critical($msg, $class="\Ew\Exception")
    {
        $e = new $class($msg);
        $e->isCritical=true;
        $e->logo='js/jqueryda/img/error.png';
        static::handler($e);
    }
    
    static public function toCritical(\Exception $e, $class="\Ew\Exception")
    {
        $e = new $class($e->getMessage(), 13, $e);
        $e->isCritical=true;
        $e->logo='js/jqueryda/img/error.png';
        static::handler($e);
    }
    
    static public function toClient(\Exception $e, $class="\Ew\Exception")
    {
        $e = new $class($e->getMessage(), 13, $e);
        $e->isCritical=true;
        $e->logo='js/jqueryda/img/error.png';
        static::_process($e);
    }
    
    static public function notValid($table, array $fieldList = Array(), $mensagem=null, $class='\Ew\Db\Table\NotValidException')
    {
        if($mensagem===null){
            $mensagem = "Existe um erro em [FIELDS]";
        }
        $fields = '';
        foreach($fieldList as $field => $value)
        {
            if($fields!=''){
                $fields.=', ';
            }
            if($field*1==0 && $field != 0){
                $fields .= $table->getLabel($field) . " = '" . $value . "'";
            }else{
                $fields .= $table->getLabel($value);
            }
        }
        $mensagem = str_replace('[TABLE]', $table->getLabelOfTable($table->getPath()), $mensagem);
        $mensagem = str_replace('[FIELDS]', $fields, $mensagem);
        
        $e = new $class($mensagem);
        $e->fields = $fieldList;
        $e->isCritical = true;
        \Ew\Error::handler($e);
    }
    
    static function log(\Ew\Exception $e)
    {
    
    }
        
    
}
