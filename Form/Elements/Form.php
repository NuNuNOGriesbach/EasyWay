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
use Ew\Error;

/**
 * Description of Form
 *
 * @author nununo
 */
class Form extends Container{
    protected $_type = 'Form';
    
    protected $_active = Array();
    
    protected $_hasActivePager = false;
    protected $_hasActivePage = false;
    protected $_hasActiveGroup = false;
    protected $_hasActiveLine = false;
    protected $_hasActiveBar = false;
    protected $_hasActiveGroupLine = false;
    
    public function __construct($name, $attribs=null)
    {
        parent::__construct($name, $attribs);        
    }
    
    
            
    public function add($child)
    {
        if(!empty($this->_active)){
            $this->_active[count($this->_active)-1]->add($child);
            return $this;
        }
        if($child instanceof Page){
            throw new \Exception("O formulário {$this->getId()} não pode conter páginas sem um paginador: {$child->getid()}");
        }
        return parent::add($child);
    }
    
    public function group($label, $attribs=Array())
    {
        if($this->_hasActiveLine){
            $this->endLine();
        }
        
        if($this->_hasActiveBar){
            $this->endBar();
        }
        
        if($this->_hasActiveGroup){
            $this->endGroup();
        }
        
        $new = new Group($attribs);
        $new->setLabel($label);
        $this->add($new);
        array_push($this->_active, $new);
        $this->_hasActiveGroup = true;
        return $this;
    }
    
    public function endGroup()
    {
        if(!$this->_hasActiveGroup){
            return $this;
        }
        if($this->_hasActiveLine){
            $this->endLine();
        }
        if($this->_hasActiveBar){
            $this->endBar();
        }
        array_pop($this->_active);
        $this->_hasActiveGroup=false;
        return $this;
    }
    
    public function groupLine($label, $attribs=Array())
    {
                
        if($this->_hasActiveGroup){
            $this->endGroup();
        }
        $this->line();
        $this->_hasActiveLine = false;
        $new = new Group($attribs);
        $new->setLabel($label);
        $this->add($new);
        array_push($this->_active, $new);
        $this->_hasActiveGroupLine = true;
        $this->_hasActiveGroup = true;
        return $this;
    }
    
    public function endGroupLine()
    {
        if($this->_hasActiveGroupLine){
            $this->endLine();
        }
        if($this->_hasActiveBar){
            $this->endBar();
        }
        $this->_hasActiveGroupLine = false;
        if(!$this->_hasActiveGroup){
            return $this;
        }
        array_pop($this->_active);
        $this->_hasActiveGroup=false;
        
        return $this;
    }
    
    public function pager($attribs=Array())
    {
        if($this->_hasActiveGroup){
            $this->endGroup();
        }
        
        if($this->_hasActivePage){
            $this->endPage();
        }
        
        if($this->_hasActivePager){
            $this->endPager();
        }
        if($this->_hasActiveLine){
            $this->endLine();
        }
        if($this->_hasActiveBar){
            $this->endBar();
        }
        
        $new = new Pager($attribs);
        
        $this->add($new);
        $this->_hasActivePager = true;
        array_push($this->_active, $new);
        return $this;
    }
    
    public function endPager()
    {
        if(!$this->_hasActivePager){
            return $this;
        }
        if($this->_hasActivePage){
            $this->endPage();
        }
        if($this->_hasActiveLine){
            $this->endLine();
        }
        if($this->_hasActiveBar){
            $this->endBar();
        }
        
        array_pop($this->_active);
        $this->_hasActivePager = false;
        return $this;
    }
    
    public function page($label, $attribs=Array())
    {
        if(!$this->_hasActivePager){
            $this->pager();
        }
        
        if($this->_hasActiveGroup){
            $this->endGroup();
        }
        
        if($this->_hasActivePage){
            $this->endPage();
        }
        if($this->_hasActiveLine){
            $this->endLine();
        }
        if($this->_hasActiveBar){
            $this->endBar();
        }
        
        $new = new Page($attribs);
        $new->setLabel($label);
        $this->add($new);
        array_push($this->_active, $new);
        $this->_hasActivePage = true;
        return $this;
    }
    
    public function endPage()
    {
        if(!$this->_hasActivePage){
            return $this;
        }
        array_pop($this->_active);
        
        $this->_hasActivePage = false;
        return $this;
    }
    
    public function Line($align='justify', $attribs = Array())
    {
        if($this->_hasActiveLine){
            $this->endLine();
        }
        
        if(!is_array($attribs)){
            $attribs = Array('justitfyElement'=>$attribs);
        }
        $new = new Line($attribs);        
        $new->setAlign($align);
        
        $this->add($new);
        array_push($this->_active, $new);
        $this->_hasActiveLine = true;
        return $this;
    }
    
    public function endLine()
    {
        if(!$this->_hasActiveLine){
            return $this;
        }
        array_pop($this->_active);
        
        $this->_hasActiveLine = false;
        return $this;
    }
    
    public function Bar($align='justify', $attribs = Array())
    {
        if($this->_hasActiveBar){
            $this->endBar();
        }
        
        if(!is_array($attribs)){
            $attribs = Array('justitfyElement'=>$attribs);
        }
        $new = new Bar($attribs);        
        $new->setAlign($align);
        
        $this->add($new);
        array_push($this->_active, $new);
        $this->_hasActiveBar = true;
        return $this;
    }
    
    public function endBar()
    {
        if(!$this->_hasActiveBar){
            return $this;
        }
        array_pop($this->_active);
        
        $this->_hasActiveBar = false;
        return $this;
    }
    
    public function end()
    {
        if($this->_hasActivePager){
            return $this->endPager();
        }elseif($this->_hasActiveGroup){
            return $this->endGroup();
        }elseif($this->_hasActivePage){
            return $this->endPage();
        }elseif($this->_hasActiveLine){
            return $this->endLine();
        }elseif($this->_hasActiveBar){
            $this->endBar();
        }else{
            return $this->endForm();
        }
    }
    
    public function form(array $attribs = Array())
    {
        foreach($attribs as $attrib => $value){
            if($attrib=='parent'){
                $this->set('parentIfNotParent', $value);
            }else{
                $this->set($attrib, $value);
            }
        }
        return $this;
    }
    
    public function endForm()
    {
        if($this->_hasActivePager){
            $this->endPager();
        }elseif($this->_hasActiveGroup){
            $this->endGroup();
        }elseif($this->_hasActivePage){
            $this->endPage();
        }elseif($this->_hasActiveLine){
            $this->endLine();
        }elseif($this->_hasActiveBar){
            $this->endBar();
        }
        return $this->render();
    }
            
    /**
     * Define a largura do form na tela, em pontos ou percentual
     * @param type $width
     * @return \Ew\Form\Elements\Group
     */
    public function setWidth($width)
    {
        return $this->set('width', $width);
    }
    
    /**
     * retorna a largura do form na tela, em pontos ou percentual
     * @return string
     */
    public function getWidth()
    {
        return $this->get('width');
    }
    
    public function setRenderIn($viewElement = 'body'){
        $this->set('parentIfNotParent', $viewElement);
    }
    
    
}
