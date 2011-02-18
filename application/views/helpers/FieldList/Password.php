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
        $id = $this->_getId();
        $name = $this->_name;
        $html = "<input type=\"password\" value=\"$value\" name=\"$name\" autocomplete=\"off\" id=\"$id\" $class />";
        
        return $html;
    }
}