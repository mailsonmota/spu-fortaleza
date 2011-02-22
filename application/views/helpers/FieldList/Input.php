<?php
require_once 'Abstract.php';
abstract class Zend_View_Helper_FieldList_Input extends Zend_View_Helper_FieldList_Abstract
{
	const AFTER_LABEL = ':';
	
    protected $_label;
    protected $_name;
    protected $_value;
    
    public function __construct($label = '', $name = '', $value = '', array $options = array()) {
        $this->_label = $label;
        $this->_name = $name;
        $this->_options = $options;
        $this->_value = $this->_renderInput($value);
        return parent::__construct($options);
    }
    
    protected function _renderInput($value)
    {
        return $value;
    }
    
    protected function _render()
    {
       $html  = $this->_renderLabelItem();
       $html .= $this->_renderValueItem();
       
       return $html;
    }
    
    private function _renderLabelItem()
    {
        $html = $this->_renderLabelItemBefore() . $this->_renderLabel() . $this->_renderLabelItemAfter();
        return $html;
    }
    
    protected function _renderLabelItemBefore()
    {
        $html = $this->_openTagForElement(self::LABEL_ITEM_BEFORE);
        return $html;
    }
    
    protected function _renderLabel()
    {
        $class = ($this->_isRequired()) ? 'class="required"' : '';
        return "<label $class>" . $this->_label . self::AFTER_LABEL . '</label>';
    }
    
    protected function _renderLabelItemAfter()
    {
        $html = $this->_closeTagForElement(self::LABEL_ITEM_AFTER);
        return $html;
    }
    
    private function _renderValueItem()
    {
        $html = $this->_renderValueItemBefore() . $this->_renderValue() . $this->_renderValueItemAfter();
        return $html;
    }
    
    protected function _renderValueItemBefore()
    {
        $html = $this->_openTagForElement(self::VALUE_ITEM_BEFORE);
        return $html;
    }
    
    protected function _renderValue()
    {
        return $this->_value;
    }
    
    protected function _renderValueItemAfter()
    {
        $html = $this->_closeTagForElement(self::VALUE_ITEM_AFTER);
        return $html;
    }
    
    protected function _getId()
    {
        return ($this->_getOption('id')) ? $this->_getOption['id'] : $this->_name;
    }
    
    protected function _isRequired()
    {
        return ($this->_getOption('required')) ? $this->_getOption('required') : false;
    }
    
    protected function _getInputClass()
    {
        return ($this->_isRequired()) ? 'class="required"' : '';
    }
}