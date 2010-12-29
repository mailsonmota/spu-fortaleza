<?php
require_once 'Abstract.php';
class Zend_View_Helper_FieldList_Text extends Zend_View_Helper_FieldList_Abstract
{
	public function text($label = '', $value = '', array $options = array()) {
		$this->_label = $label;
		$this->_value = $value;
        return parent::__construct($options);
    }
    
    protected function _renderValueItemBefore()
    {
        $html = $this->_openTagForElement(self::VALUE_ITEM_BEFORE . ' class="campoTexto"');
        return $html;
    }
}