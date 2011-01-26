<?php
require_once 'Abstract.php';
class Zend_View_Helper_Protocolo_Select extends Zend_View_Helper_Protocolo_Abstract
{
    public function select($ajaxUrl, $options = array())
    {
        return parent::__construct($ajaxUrl, $options);
    }
    
    public function render() {
        return parent::render();
    }
    
    protected function _getBeforeHeaderColumns()
    {
        return '<th></th>';
    }
    
    protected function _getNumberOfColumns()
    {
    	return 2;
    }
}