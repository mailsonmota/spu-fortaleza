<?php
class Spu_Entity_Formulario extends Spu_Entity_Abstract
{
    protected $_data;
    
    public function hasData()
    {
        return (isset($this->_data));
    }
    
    public function getData() {
        return $this->_data;
    }
    
    public function setData($value)
    {
        $this->_data = $value;
    }
}