<?php
require_once 'Input.php';
class Zend_View_Helper_FieldList_Textbox extends Zend_View_Helper_FieldList_Input
{
    public function textbox($label = '', $name = '', $value = '', array $options = array()) {
        return parent::__construct($label, $name, $value, $options);
    }
    
    protected function _renderInput($value)
    {
    	$html = "<input type=\"text\" value=\"$value\" name=\"" . $this->_name . "\" />";
    	
    	return $html;
    }
}