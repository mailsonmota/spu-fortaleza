<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_tabs extends Zend_View_Helper_Abstract
{
    const LIST_CLASS = 'tabs';
    protected $_options = array();
    protected $_activeTab;
    
    public function tabs()
    {
        return $this;
    }
    
    public function openList()
    {
        $html  = '<ul class="' . self::LIST_CLASS . '">';
        return $html;
    }
    
    public function addTab($url, $name, $active = false, array $options = array())
    {
        $this->_options = $options;
        $liClass = ($active) ? 'class="active"' : '';
        $html = "<li $liClass><a href=\"$url\">$name</a></li>";
        return $html;
    }
    
    public function closeList()
    {
        $html  = '</ul>';
        return $html;
    }
}