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
 * Agrupador de linhas, util quando várias linhas tem a mesma semâncica
 * 
 * Por exemplo, agrupar as linhas que contém o endereço, município, estado e cep
 *
 * @author nununo
 */
class Group extends Form {
    protected $_type = 'Group';
    
    protected function _setDefaultsAttribs()
    {
        $this->_default('label', '...');
        //$this->_default('size', '1');
    }
    
    /**
     * Define o nome do grupo de campos
     * @param type $label
     * @return \Ew\Form\Elements\Group
     */
    public function setLabel($label)
    {
        return $this->set('label',$label);
    }
    
    /**
     * Retorna o nome do agrupamento
     * @return string
     */
    public function getLabel()
    {
        return $this->get('label');
    }
    
    /**
     * Define a largura do grupo na tela, em pontos ou percentual
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
    
    /**
     * Tipo de alinhamento do grupo em relação a outros grupos
     * 'group' -> Um grupo por linha
     * 'left' -> Tenta colocar o grupo ao lado do grupo anterior
     * @param string $align tipo de alinhamento para os elementos contidos
     */
    public function setAlign($align)
    {
        return $this->set('align', $align);
    }
    
    /**
     * Retorna o tipo de alinhamento
     * @return string
     */
    public function getAlign(){
        return $this->get('align');
    }
}
