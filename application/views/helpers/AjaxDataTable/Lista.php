<?php
require_once 'Abstract.php';
class Zend_View_Helper_AjaxDataTable_Lista extends Zend_View_Helper_AjaxDataTable_Abstract
{
	protected $_pageSize = 10;
	
    public function lista($ajaxUrl = '', array $columns = array(), array $options = array())
    {
    	return parent::__construct($ajaxUrl, $columns, $options);
    }
    
    public function render() {
    	return parent::render();
    }
}