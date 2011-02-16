<?php
require_once 'Input.php';
class Zend_View_Helper_FieldList_Password extends Zend_View_Helper_FieldList_Input
{
    public function password($label = '', $name = '', $value = '', array $options = array()) {
        return parent::__construct($label, $name, $value, $options);
    }
    
    protected function _renderInput($value)
    {
    	$html = "<input type=\"password\" value=\"$value\" name=\"" . $this->_name . "\" />";
    	
    	return $html;
    }
}