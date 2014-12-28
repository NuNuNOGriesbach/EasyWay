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

class Cliente extends \Ew\Model\DataDicionary{
    
    protected $_fieldMap = Array(
        'id' => Array(
            'name' => 'id',
            'label' => 'Id',
            'type' => 'TextField',
            'dataType' => 'number',
            'size' => 10,
        ),
        'nome' => Array(
            'type' => 'TextField',
            'label' => 'Nome',
            'dataType' => 'text',            
            'size' => 40,            
        ),
        'rua' => Array(
            'type' => 'TextField',
            'label' => 'Logradouro',
            'dataType' => 'text',
            'size' => 40,
        ),
        'numero' => Array(
            'name' => 'id',
            'label' => 'Número',
            'type' => 'TextField',
            'dataType' => 'number',
            'size' => 10,
        ),
        'complemento' => Array(
            'type' => 'TextField',
            'label' => 'Complemento',
            'dataType' => 'text',
            'size' => 20,
        ),
        'bairro' => Array(
            'type' => 'TextField',
            'label' => 'Bairro',
            'dataType' => 'text',
            'size' => 40,
        ),
        'cidade' => Array(
            'type' => 'TextField',
            'label' => 'Cidade',
            'dataType' => 'text',
            'size' => 40,
        ),
        'uf' => Array(
            'type' => 'TextField',
            'label' => 'UF',
            'dataType' => 'upperText',
            'size' => 2,
        ),
        'cep' => Array(
            'type' => 'TextField',
            'label' => 'CEP',
            'dataType' => 'number',
            'size' => 8,
        ),
        'ruac' => Array(
            'type' => 'TextField',
            'label' => 'Logradouro',
            'dataType' => 'text',
            'size' => 40,
        ),
        'numeroc' => Array(
            'name' => 'id',
            'label' => 'Número',
            'type' => 'TextField',
            'dataType' => 'number',
            'size' => 10,
        ),
        'complementoc' => Array(
            'type' => 'TextField',
            'label' => 'Complemento',
            'dataType' => 'text',
            'size' => 20,
        ),
        'bairroc' => Array(
            'type' => 'TextField',
            'label' => 'Bairro',
            'dataType' => 'text',
            'size' => 40,
        ),
        'cidadec' => Array(
            'type' => 'TextField',
            'label' => 'Cidade',
            'dataType' => 'text',
            'size' => 40,
        ),
        'ufc' => Array(
            'type' => 'TextField',
            'label' => 'UF',
            'dataType' => 'upperText',
            'size' => 2,
        ),
        'cepc' => Array(
            'type' => 'TextField',
            'label' => 'CEP',
            'dataType' => 'number',
            'size' => 8,
        ),
        'ddd_telefone' => Array(
            'type' => 'TextField',
            'label' => 'DDD',
            'dataType' => 'number',
            'size' => 3,
            //'maxWidth' => '70',
        ),
        'telefone' => Array(
            'type' => 'TextField',
            'label' => 'Telefone fixo',
            'dataType' => 'number',
            'size' => 10,
            //'maxWidth' => '190',
        ),
        'ddd_celular' => Array(
            'type' => 'TextField',
            'label' => 'DDD',
            'dataType' => 'number',
            'size' => 3,
            //'maxWidth' => '70',
        ),
        'celular' => Array(
            'type' => 'TextField',
            'label' => 'Celular',
            'dataType' => 'number',
            'size' => 10,
            //'maxWidth' => '190',
        ),
        'ddd_contato' => Array(
            'type' => 'TextField',
            'label' => 'DDD',
            'dataType' => 'number',
            'size' => 3,
            //'maxWidth' => '70',
        ),
        'contato' => Array(
            'type' => 'TextField',
            'label' => 'Contato',
            'dataType' => 'number',
            'size' => 10,
            //'maxWidth' => '190',
        ),
        'ddd_comercial' => Array(
            'type' => 'TextField',
            'label' => 'DDD',
            'dataType' => 'number',
            'size' => 3,
            //'maxWidth' => '70',
        ),
        'comercial' => Array(
            'type' => 'TextField',
            'label' => 'Telefone comercial',
            'dataType' => 'number',
            'size' => 10,
            //'maxWidth' => '190',
        ),
        'ramal' => Array(
            'type' => 'TextField',
            'label' => 'Ramal',
            'dataType' => 'number',
            'size' => 4,
            //'maxWidth' => '70',
        ),
        
        
    );
}
