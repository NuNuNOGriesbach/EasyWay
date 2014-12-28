<?php

namespace Ew;

/**
 * Exceção customizada para melhor gerenciamento no lado cliente, dos problemas ocorridos em outras camadas.
 *
 * @author nununo
 */
class Exception extends \Exception {
    /**
     * Lista de campos, quando houverem, que causaram a exceção para que o frontend possa marcá-los, ou selecioná-los....
     * @var Array 
     */
    public $fields = Array();
    
    /**
     * Exceções criticas interrompem o processamento a partir do momento em que são emitidas (throw)
     * @var type 
     */
    public $isCritical = true;
    
    /**
     * Adiciona ao arquivo de log a ocorrencia de uma exceção
     * @var type 
     */
    public $isLog = false;
    
    /**
     * Nome de uma função jscript para ser executada no client-side caso esta exceção seja lançada
     * @var type 
     */
    public $clientEvent = '';
    
    public $logo = 'error.png';
    
    public $type = 'error';
    
    public function render()
    {
        return Array('msg' => $this->getMessage(), 'type' => $this->type, 'fields' => $this->fields, 'event' => $this->clientEvent, 'trace' => $this->getTrace(), 'logo'=>$this->logo);
    }
}
