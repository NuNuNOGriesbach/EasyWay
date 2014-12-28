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

namespace Ew;

/**
 * Description of Command
 *
 * @author nununo
 */
class Command {
    
    protected static $_commands = Array();
    protected static $_creates = Array();
    
    
    /**
     *
     * @var Command\Config 
     */
    protected static $_config = null;
    
    /**
     * Inicializa o objeto que compartilha configurações entre cliente e servidor
     * @return Command\Config
     */ 
    public static function config()
    {
        if(static::$_config===null ){
            $command = new Command\Config();        
            static::$_commands[] = $command;

            static::$_config = $command;
        }
        return static::$_config; 
    }
    
    /**
     * Envia um comando create para o cliente
     * @param Element|Array $element
     */
    public static function create($element)
    {
        $create = new Command\Create();
        $create->add($element);
        static::$_creates[] = $create;
    }
    
    public static function getCommands()
    {
        return static::$_commands;
    }
    
    public static function error($exception)
    {
        $command = new Command\Error();
        $command->add($exception);
        static::$_commands[] = $command;
    }
    
    public static function bindServerEvent($event)
    {
        $command = new Command\Event();
        $command->add($event);
        static::$_commands[] = $command;
    }
    
    public static function getResponse()
    {
        $response = Array();
       // static::$_config->insertCommandsToClient($response);
        
        foreach(static::$_creates as $create){
            $create->insertCommandsToClient($response);
        }
        
        foreach(static::$_commands as $command){
            $command->insertCommandsToClient($response);
        }
        return $response;
    }
    
}
