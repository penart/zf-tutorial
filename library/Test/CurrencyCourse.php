<?php

/**
* Класс курса валют
*/
class Test_CurrencyCourse
{
    private $_numericCode;  //цЦифровой код валюты
    private $_charCode;     //символьный код валюты
    private $_nominal;      //единица
    private $_name;         //наименование 
    private $_value;        //текущий курс
    
    public function setNumericCode($numericCode)
    {
        $this->_numericCode = $numericCode;
    }

    public function getNumericCode()
    {
        return $this->_numericCode;
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
    
    public function setValue($value)
    {
        $this->_value = $value;
    }

    public function getValue()
    {
        return $this->_value;
    }
}
