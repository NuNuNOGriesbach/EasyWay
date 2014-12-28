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
class Create extends CommandAbstract{
    
    protected $_name = 'Create';
    
    public function add($element)
    {
        if($element instanceof \Ew\Form\Elements\Element){
            $this->_commands[] = $element->render();
            return;
        }
        $this->_commands[] = $element;
    }
}
