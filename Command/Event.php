<?php

namespace Ew\Command;

/**
 * Description of Event
 *
 * @author nununo
 */
class Event extends CommandAbstract{
    protected $_name = 'Event';
    
    public function add($eventArray)
    {
        $this->_commands[] = $eventArray;
    }
}
