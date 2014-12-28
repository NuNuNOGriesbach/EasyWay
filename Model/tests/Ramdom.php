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

namespace Ew\Model\tests;

/**
 * Description of Cliente
 *
 * @author nununo
 */

class Ramdom extends \Ew\Model\DataDicionary{
    
    protected $_fieldMap = Array(
        
        'ramal' => Array(
            'type' => 'TextField',
            'label' => 'Ramal',
            'dataType' => 'number',
            'size' => 4,
            //'maxWidth' => '70',
        ),
        
        
    );
    
    /**
     * Ponto de customização para dicionários personalizados
     */
    public function init()
    {
        for($x = 1; $x< 40 ; $x++){
            $size = round(rand(8, 50));
            
            $this->_fieldMap['campo' . $x] = Array(
                'type' => 'TextField',
                'label' => 'Size ' . $size,
                'dataType' => 'number',
                'size' => $size,
            );
        }
        
        
    }
}
