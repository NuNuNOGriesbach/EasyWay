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

namespace Ew\Form\Elements;

/**
 * Description of Margin
 *
 * @author nununo
 */
class Margin extends Element {
    protected $_type = 'Margin';
    /**
     * Define a largura do espaçamento na tela, em pontos ou percentual
     * @param type $width
     * @return \Ew\Form\Elements\Group
     */
    public function setWidth($width)
    {
        return $this->set('width', $width);
    }
    
    /**
     * retorna a largura do grupo na tela, em pontos ou percentual
     * @return string
     */
    public function getWidth()
    {
        return $this->get('width');
    }
}
