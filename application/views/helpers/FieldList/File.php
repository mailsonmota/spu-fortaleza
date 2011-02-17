<?php
require_once 'Input.php';
class Zend_View_Helper_FieldList_File extends Zend_View_Helper_FieldList_Input
{
    public function file($label = '', $name = '', $value = '', array $options = array()) {
        return parent::__construct($label, $name, $value, $options);
    }
    
    protected function _renderInput($value)
    {
    	$class = $this->_getInputClass();
    	$html = "<input type=\"file\" name=\"" . $this->_name . "\" $class />";
    	
    	return $html;
    }
}