<?php
namespace Ew\Minimal;
use Ew\Helper\FormatString;
/**
 * Bootstrap minimalista para aplicações independentes de outros frameworks
 *
 * @author nununo
 */
class BootstrapAbstract {
    protected $_params;
    public function __construct($params = Array()) {
        $this->_params = $params;
        $this->_params += $_REQUEST;
        $this->init();
    }
    
    /**
     * Método chamado após o constructor, para inicializar database, serviços, constantes, etc...
     */
    public function init()
    {
        
    }
    
    /***
     * Instancia um controler e executa uma action
     */
    public function run($module, $controller, $action)
    {
       $class = 'App\\' . FormatString::urlToCamelCase($module) . '\\' . FormatString::urlToCamelCase($controller) . "Controller";
       $obj = new $class($this->_params);
       $method = FormatString::urlToCamelCase($action) . 'Action';
       $obj->$method();
    }
}
