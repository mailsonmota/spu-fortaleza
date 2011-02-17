<?php
require_once 'Input.php';
class Zend_View_Helper_FieldList_Checkbox extends Zend_View_Helper_FieldList_Input
{
    public function checkbox($label = '', $name = '', $value = false, array $options = array()) {
        return parent::__construct($label, $name, $value, $options);
    }
    
    protected function _renderInput($value)
    {
    	$checked = ($this->_value) ? 'checked="checked"' : '';
    	
    	$html = "<input type=\"checkbox\" name=\"" . $this->_name . "\" $checked />";
    	
    	return $html;
    }
}