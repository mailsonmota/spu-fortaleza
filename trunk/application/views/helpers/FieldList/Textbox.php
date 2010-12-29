<?php
require_once 'Abstract.php';
class Zend_View_Helper_FieldList_Textbox extends Zend_View_Helper_FieldList_Abstract
{
    public function textbox($label = '', $value = '', array $options = array()) {
        $this->_label = $label;
        $this->_value = $this->_renderTextbox($value);
        return parent::__construct($options);
    }
    
    protected function _renderTextbox($value)
    {
    	$html = "<input type=\"text\" value=\"$value\" />";
    	
    	return $html;
    }
}