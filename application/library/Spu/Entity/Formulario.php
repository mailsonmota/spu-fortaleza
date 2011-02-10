<?php
class Formulario extends BaseEntity
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