<?php
require_once 'Abstract.php';
class Zend_View_Helper_FieldList_Open extends Zend_View_Helper_FieldList_Abstract
{
	public function open(array $options = array()) {
		return parent::__construct($options);
	}
	
	protected function _render()
	{
		$html = $this->_renderListOpen();
        
        return $html;
	}
	
    protected function _renderListOpen()
    {
        return $this->_openTagForElement(self::LIST_OPEN . ' class="form"');
    }
}