<?php

/*
 * Copyright (C) 2014 =NuNuNO== Griesbach
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
 * Description of Page
 *
 * @author nununo
 */
class Page extends Form{
    protected $_type = 'Page';
    
    /**
     * Define o título da página
     * @param string $value
     * @return \Ew\Form\Elements\TextField
     */
    public function setLabel($value)
    {
        return $this->set('label', $value);
    }
    
    /**
     * retorna o título da página
     * @return string
     */
    public function getLabel()
    {
        return $this->get('label');
    }
}
