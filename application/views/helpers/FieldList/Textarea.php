<?php
require_once 'Input.php';
class Zend_View_Helper_FieldList_Textarea extends Zend_View_Helper_FieldList_Input
{
    public function textarea($label = '', $name = '', $value = '', array $options = array()) {
        return parent::__construct($label, $name, $value, $options);
    }
    
    protected function _renderInput($value)
    {
    	$html = "<textarea name=\"" . $this->_name . "\">$value</textarea>";
    	
    	return $html;
    }
}