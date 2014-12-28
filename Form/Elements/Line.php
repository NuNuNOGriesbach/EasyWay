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
 * Uma linha contendo elementos
 * 
 * Exemplo, os campos ID e nome poderiam ser colocados na mesma linha 
 *
 * @author nununo
 */
class Line extends Container{
    protected $_type = 'Line';
    
    protected function _setDefaultsAttribs()
    {
        $this->_default('align', 'justify');
        $this->_default('margin', '0');
    }
    
        
    /**
     * Tipo de alinhamento da linha
     * 'justify' -> Campos tomam 100% da largura do container e garantem alinhamento dos lados direitos dos ultimos elementos
     * 'left' -> Campos sem alinhamento, usando o tamanho padrão pelo tipo de conteúdo
     * 'pseudoJustify' -> define o tamanho da Linha para o mesmo tamanho de um elemento especificado por justitfyElement 
     * 'pseudoJustifyHalf' -> define o tamanho da Linha para a metade do tamanho de um elemento especificado por justitfyElement 
     * @param string $align tipo de alinhamento para os elementos contidos
     * @param string $justitfyElement Elemento de referencia para pseudo justificação
     */
    public function setAlign($align, $justitfyElement=null)
    {
        $this->set('align', $align);
        if($align=='pseudoJustify' || $align=='pseudoJustifyHalf'){
            if($justitfyElement===null){
                throw new \Exception("Para alinhamento de linha do tipo $align é necessário informar um elemento de referencia");
            }
            $this->set('justitfyElement',$justitfyElement);
        }
        return $this;
    }
    
    public function getAlign(){
        return $this->get('align');
    }
    
    public function getJustitfyElement(){
        return $this->get('justitfyElement');
    }
    
    public function setMargin($left)
    {
        return $this->set('margin', $left);
    }
    
    public function getMargin()
    {
        return $this->get('margin');
    }
    
    
}
