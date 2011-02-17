<?php
require_once 'Input.php';
class Zend_View_Helper_FieldList_Checklist extends Zend_View_Helper_FieldList_Input
{
	protected $_checkOptions;
	
    public function checklist($label, $name = '', $checkOptions = array(), $checkedValues = array(), $options) {
    	$this->_checkOptions = $checkOptions;
        return parent::__construct($label, $name, $checkedValues, $options);
    }
    
    protected function _renderInput($checkedValues)
    {
    	$html = '';
    	if (is_array($this->_checkOptions)) {
    		$id = $this->_getId();
    		$name = $this->_name;
    		
	        $html .= "<ul>";
	        $i = 0;
	        foreach ($this->_checkOptions as $key => $value) {
	            $liClass = ((++$i % 2) == 0) ? 'class="odd"' : 'class="even"';
	            $checked = (in_array($key, $checkedValues)) ? 'checked="checked"' : null;
	            $html .= "<li $liClass>";
	            $html .= "<input type=\"checkbox\" name=\"$name\" id=\"$id\" value=\"$key\" $checked/>$value";
	            $html .= "</li>";
	        }
	        $html .= "</ul>";
    	}
        
    	return $html;
    }
}