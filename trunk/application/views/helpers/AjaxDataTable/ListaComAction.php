<?php
require_once 'Abstract.php';
class Zend_View_Helper_AjaxDataTable_ListaComAction extends Zend_View_Helper_AjaxDataTable_Abstract
{
    public function listaComAction($ajaxUrl, $options = array())
    {
    	return parent::__construct($ajaxUrl, $options);
    }
    
    public function render() {
        return parent::render();
    }
    
    protected function _getAfterHeaderColumns()
    {
        return '<th>Ações</th>';
    }
    
    protected function _getNumberOfColumns()
    {
        return 2;
    }
}