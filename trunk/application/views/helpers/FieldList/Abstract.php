<?php
class Zend_View_Helper_FieldList_Abstract extends Zend_View_Helper_Abstract
{
    const LIST_OPEN = 'dl';
    const LIST_CLOSE = 'dl';
    const LABEL_ITEM_BEFORE = 'dt';
    const LABEL_ITEM_AFTER = 'dt';
    const VALUE_ITEM_BEFORE = 'dd';
    const VALUE_ITEM_AFTER = 'dd';
    
    protected $_options;
    
    public function __construct(array $options = array())
    {
        $this->_options = $options;
        
        return $this->_render();
    }
    
    protected function _render() 
    {
    }
    
    protected function _openTagForElement($element)
    {
        $html = '<' . $element . '>';
        return $html;
    }
    
    protected function _closeTagForElement($element)
    {
        $html = '</' . $element . '>';
        return $html;
    }
    
    protected function _getOption($option)
    {
        return (is_array($this->_options) AND isset($this->_options[$option])) ? $this->_options[$option] : null;
    }
}