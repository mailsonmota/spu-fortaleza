<?php
require_once 'Abstract.php';
class Zend_View_Helper_Protocolo_ListaComAction extends Zend_View_Helper_Protocolo_Abstract
{
    public function listaComAction($protocolos = array(), $options = array())
    {
    	return parent::__construct($protocolos, $options);
    }
    
    public function render() {
        return parent::render();
    }
    
    protected function _getAfterHeaderColumns()
    {
        return '<th></th>';
    }
    
    protected function _getAfterBodyColumns($protocolo)
    {
       return '<td>' . $this->_renderActionColumn($protocolo) . '</td>';
    }
    
    protected function _renderActionColumn($protocolo)
    {
        $href = $this->_getHref($protocolo);
        $title = $this->_getTitle();
        $html = "<a href=\"$href\">$title</a>";
        
        return $html;
    }
    
    protected function _getHref($protocolo)
    {
    	return $this->_getBaseUrl() . '/id/' . $protocolo->id;
    }
    
    protected function _getBaseUrl()
    {
        $baseUrl = '';
        if (isset($this->_options['baseUrl'])) {
            $baseUrl = $this->_options['baseUrl'];
        }
        
        return $baseUrl;
    }
    
    protected function _getTitle()
    {
        $title = '';
        if (isset($this->_options['title'])) {
            $title = $this->_options['title'];
        }
        
        return $title;
    }
    
    protected function _getNumberOfColumns()
    {
        return 2;
    }
}