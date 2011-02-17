<?php
require_once 'Input.php';
class Zend_View_Helper_FieldList_Select extends Zend_View_Helper_FieldList_Input
{
	protected $_selectOptions;
	
    public function select($label = '', $name = '', $selectOptions = array(), $value = '', $options = array()) {
    	$this->_selectOptions = $selectOptions;
        return parent::__construct($label, $name, $value, $options);
    }
    
    protected function _renderInput($selectedValue)
    {
    	$multiple = ($this->_isMultiple()) ? 'multiple="multiple"' : '';
    	$class = $this->_getInputClass();
    	
    	$html  = "<select $multiple name=\"" . $this->_name . "\" id=\"" . $this->_getId() . "\" $class>";
        if (is_array($this->_selectOptions)) {
            foreach ($this->_selectOptions as $optionValue => $optionText) {
                $selected = ($optionValue == $selectedValue) ? 'selected="selected"' : '';
                $html .= "<option value=\"$optionValue\" $selected>$optionText</option>";
            }
        }
        $html .= '</select>';
    	
    	return $html;
    }
    
    protected function _isMultiple()
    {
    	return (isset($this->_options['multiple'])) ? $this->_options['multiple'] : false;
    }
}