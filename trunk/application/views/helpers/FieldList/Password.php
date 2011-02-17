<?php
require_once 'Textbox.php';
class Zend_View_Helper_FieldList_Password extends Zend_View_Helper_FieldList_Textbox
{
    public function password($label = '', $name = '', $value = '', array $options = array()) {
        return parent::textbox($label, $name, $value, $options);
    }
    
    protected function _renderInput($value)
    {
    	$class = $this->_getInputClass();
    	$html = "<input type=\"password\" value=\"$value\" name=\"" . $this->_name . "\" $class />";
    	
    	return $html;
    }
}