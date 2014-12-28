<?php

/*
 * Copyright (C) 2014 nununo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Ew\Command;

/**
 * Description of Create
 *
 * @author nununo
 */
class Config extends CommandAbstract{
    
    protected $_name = 'Config';
    protected $_configs = Array();
    
    public function add($param, $value)
    {
        $this->_configs[$param] = $value;
        return $this;
    }
    
    public function get($param, $default=null)
    {
        if(isset($this->_configs[$param])){
            return $this->_configs[$param];
        }
        return null;
    }
    
    public function setRenderJs($renderName)
    {
        return $this->add('renderJs',$renderName);
    }
    
    public function setRenderModeJs($renderName)
    {
        return $this->add('renderModeJs',$renderName);
    }
    
    public function setRenderCss($renderName)
    {
        return $this->add('renderCss',$renderName);
    }
    
    public function setRenderModeCss($renderName)
    {
        return $this->add('renderModeCss',$renderName);
    }
    
    public function getRenderJs()
    {
        return $this->get('renderJs');
    }
    
    public function getRenderModeJs()
    {
        return $this->get('renderModeJs');
    }
    
    public function getRenderCss()
    {
        return $this->get('renderCss');
    }
    
    public function getRenderModeCss()
    {
        return $this->get('renderModeCss');
    }
    
    public function insertCommandsToClient(&$toClientArray)
    {
        $toClientArray[] = Array($this->_name => $this->_configs);
    }
}
