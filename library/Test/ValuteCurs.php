<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Test_ValuteCurs
{
    private $_numCode;
    private $_charCode;
    private $_nominal;
    private $_name;
    private $_value;
    
    public function setNumCode($numCode)
    {
        $this->_numCode = $numCode;
    }

    public function getNumCode()
    {
        return $this->_numCode;
    }

    public function setCharCode($charCode)
    {
        $this->_charCode = $charCode;
    }

    public function getCharCode()
    {
        return $this->_charCode;
    }

    public function setNominal($nominal)
    {
        $this->_nominal = $nominal;
    }

    public function getNominal()
    {
        return $this->_nominal;
    }
    
    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }
    
    public function setvalue($value)
    {
        $this->_value = $value;
    }

    public function getValue()
    {
        return $this->_value;
    }
}
