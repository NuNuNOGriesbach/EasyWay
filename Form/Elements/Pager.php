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
 * Container para separação de conteúdo em páginas, podendo ser renderizado como 
 * paginador horiozontal, ou vertica (Accordion)
 *
 * @author nununo
 */
class Pager extends Container{
    protected $_type = 'Pager';
    
    protected function _setDefaultsAttribs()
    {
        $this->_default('style', 'horizontal');
    }
    
    public function add($child)
    {
        if(!$child instanceof Page){
            throw new \Exception("O agrupador de páginas {$this->getId()} não pode nada além de páginas: {$child->getid()} não é uma página.");
        }
        return parent::add($child);
    }
    
    /**
     * Estilos válidos, vertical e horizontal...
     * @param string $style
     * @return type
     */
    public function setStyle($style)
    {
        if($style!='horizontal' && $style != 'vertical'){
            throw new \Exception("O estilo do paginador {$this->getId()} deve ser 'horizontal' ou 'vertical'.");
        }
        return $this->set('margin', $left);
    }
    
    public function getStyle()
    {
        return $this->get('margin');
    }
}
