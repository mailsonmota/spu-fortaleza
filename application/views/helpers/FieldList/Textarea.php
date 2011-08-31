<?php
require_once 'Input.php';
class Zend_View_Helper_FieldList_Textarea extends Zend_View_Helper_FieldList_Input
{
    public function textarea($label = '', $name = '', $value = '', array $options = array()) {
        return parent::__construct($label, $name, $value, $options);
    }
    
    protected function _renderInput($value)
    {
        $class = $this->_getInputClass();
        $length = ($this->_getLength()) ? 'maxlength="' . $this->_getLength() . '"' : '';
        $html = "<textarea name=\"" . $this->_name . "\" $class $length>$value</textarea>";
        
        return $html;
    }
    
    protected function _getLength()
    {
    	return ($this->_getOption('length')) ? $this->_getOption('length') : 0;
    }
}