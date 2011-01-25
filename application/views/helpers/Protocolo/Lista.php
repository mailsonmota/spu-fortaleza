<?php
require_once 'Abstract.php';
class Zend_View_Helper_Protocolo_Lista extends Zend_View_Helper_Protocolo_Abstract
{
	protected $_pageSize = 10;
	
    public function lista($ajaxUrl, $options = array())
    {
    	return parent::__construct($ajaxUrl, $options);
    }
    
    public function render() {
    	return parent::render();
    }
}