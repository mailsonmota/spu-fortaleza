<?php
require_once 'Abstract.php';
class Zend_View_Helper_Protocolo_Select extends Zend_View_Helper_Protocolo_Abstract
{
    public function select($protocolos = array(), $options = array())
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
        $html = "<input type=\"radio\" name=\"$name\" value=\"$value\" />";
        
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