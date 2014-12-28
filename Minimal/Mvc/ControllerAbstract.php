<?php
namespace Ew\Minimal\Mvc;
use \Ew\Command;

/**
 * Controller minimo para sistemas sem nenhum framework
 *
 * @author nununo
 */
class ControllerAbstract {
    protected $_params = Array();
    protected $_layoutPath = "../layout/";
    protected $_isRenderHeader = true;
    protected $_header = "header.php";
    protected $_isRenderLayout = true;
    protected $_layout = "default.php";
    protected $_isRenderBottom = true;
    protected $_bottom = "bottom.php";
    protected $_viewPath = 'views/';
    
    public $view;
    
    public function __construct($params)
    {
        if(is_array($params)){
            $this->_params = $params;
        }
        $this->view = new \stdClass();
    }
    
    /**
     * Retorna um parametro do request, ou um valor padrão caso ele não exista.
     * @param type $name
     * @param type $default
     */
    public function getParam($name, $default=null)
    {
        if(isset($this->_params[$name])){
            return $this->_params[$name];
        }
        return $default;
    }
    
    public function render($view=null)
    {
        $commands = json_encode(Command::getResponse());
        
        if($view!==null){
            ob_start();
            $file = $this->_viewPath . $view;
            
            if(is_file($file)){
                include($this->_viewPath . $view);
            }
            $extraHtml = ob_get_clean();
        }
        if($this->_isRenderHeader){
            include($this->_layoutPath . $this->_header);
        }
        if($this->_isRenderLayout){
            include($this->_layoutPath . $this->_layout);
        }        
        if($this->_isRenderBottom){
            include($this->_layoutPath . $this->_bottom);
        }
    }
    
    public function renderJson($view=null)
    {
        $commands = json_encode(Command::getResponse());
        header("content-type:json");
        echo $commands;
    }
}
