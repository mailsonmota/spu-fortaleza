<?php
require_once 'Abstract.php';
class Zend_View_Helper_Protocolo_Lista extends Zend_View_Helper_Protocolo_Abstract
{
    public function lista($protocolos = array(), $options = array())
    {
    	return parent::__construct($protocolos, $options);
    }
    
    public function render() {
    	return parent::render();
    }
}