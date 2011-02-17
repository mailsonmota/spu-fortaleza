<?php
require_once 'Input.php';
class Zend_View_Helper_FieldList_Text extends Zend_View_Helper_FieldList_Input
{
    public function text($label = '', $value = '', array $options = array()) {
        return parent::__construct($label, null, $value, $options);
    }
    
    protected function _renderValueItemBefore()
    {
        $html = $this->_openTagForElement(self::VALUE_ITEM_BEFORE . ' class="campoTexto"');
        return $html;
    }
}