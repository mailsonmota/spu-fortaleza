<?php
require_once 'Input.php';
class Zend_View_Helper_FieldList_Textbox extends Zend_View_Helper_FieldList_Input
{
    public function textbox($label = '', $name = '', $value = '', array $options = array()) {
        return parent::__construct($label, $name, $value, $options);
    }
    
    protected function _renderInput($value)
    {
        $class = $this->_getInputClass();
        $id = $this->_getId();
        $html = "<input type=\"text\" value=\"$value\" name=\"" . $this->_name . "\" id=\"$id\" $class />";
        
        return $html;
    }
    
    protected function _getInputClass()
    {
        $lengthClass = $this->_getClassByLength($this->_getLength());
        $optionalClasses = $this->_getOptionalClasses();
        $requiredClass = ($this->_isRequired()) ? 'required' : '';

        $class = '';
        if ($lengthClass OR $optionalClasses OR $requiredClass) {
            $class = "class=\"" . trim("$lengthClass $optionalClasses $requiredClass") . "\"";
        }
        
        return $class;
    }
    
    protected function _getLength()
    {
        return ($this->_getOption('length')) ? $this->_getOption('length') : 0;
    }
    
    protected function _getClassByLength($length)
    {
        $class = '';
        if ($length <= 20) {
            $class = '';
        } elseif ($length <= 60) {
            $class = "middleText";
        } else {
            $class = "largeText";
        }
        
        return $class;
    }
    
    protected function _getOptionalClasses()
    {
        return $this->_getOption('class');
    }
}