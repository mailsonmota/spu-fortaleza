<?php
require_once 'Abstract.php';
class Zend_View_Helper_FieldList_Close extends Zend_View_Helper_FieldList_Abstract
{
	const LIST_CLOSE = 'dl';
	
    public function close(array $options = array()) {
        return parent::__construct($options);
    }
    
    protected function _render()
    {
        $html = $this->_renderListClose();
        
        return $html;
    }
    
    protected function _renderListClose()
    {
        return $this->_closeTagForElement(self::LIST_CLOSE);
    }
}