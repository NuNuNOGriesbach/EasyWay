<?php

namespace Ew\Command;

/**
 * Description of Error
 *
 * @author nununo
 */
class Error extends CommandAbstract{
    protected $_name = 'Error';
    
    public function add(\Ew\Exception $e)
    {
        $this->_commands[] = $e->render();
    }
}
