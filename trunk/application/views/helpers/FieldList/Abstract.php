<?php
class Zend_View_Helper_FieldList_Abstract extends Zend_View_Helper_Abstract
{
	const LABEL_ITEM_BEFORE = 'dt';
	const LABEL_ITEM_AFTER = 'dt';
	const VALUE_ITEM_BEFORE = 'dd';
	const VALUE_ITEM_AFTER = 'dd';
	const LIST_CLOSE = 'dl';
	
	protected $_label;
    protected $_value;
	protected $_options;
	
	public function __construct(array $options = array())
	{
		$this->_options = $options;
		
		return $this->_render();
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
        return '<label>' . $this->_label . '</label>';
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
}