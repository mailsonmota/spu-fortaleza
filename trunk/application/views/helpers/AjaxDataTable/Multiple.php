<?php
class Zend_View_Helper_AjaxDataTable_Multiple extends Zend_View_Helper_AjaxDataTable_Abstract
{
    public function multiple($protocolos = array(), $options = array())
    {
        return parent::__construct($protocolos, $options);
    }
    
    public function render() {
        return parent::render();
    }
    
    protected function _getBeforeHeaderColumns()
    {
        return '<th></th>';
    }
    
    protected function _getBeforeBodyColumns($protocolo)
    {
       return '<td>' . $this->_renderInputColumn($protocolo) . '</td>';
    }
    
    protected function _renderInputColumn($protocolo)
    {
        $name = $this->_getInputColumnName();
        $value = $protocolo->id;
        $html = "<input type=\"checkbox\" name=\"$name\" value=\"$value\" />";
        
        return $html;
    }
    
    protected function _getInputColumnName()
    {
        $name = '';
        if (isset($this->_options['name'])) {
            $name = $this->_options['name'];
        }
        
        return $name;
    }
    
    protected function _getNumberOfColumns()
    {
        return 2;
    }
}