<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_pagenav extends Zend_View_Helper_Abstract
{
    protected $_options = array();
    
    public function pagenav()
    {
        return $this;
    }
    
    public function openList()
    {
        $html  = '<ul class="nav">';
        return $html;
    }
    
    public function link($url, $name, array $options = array())
    {
        $this->_options = $options;
        $html = "<li><a href=\"$url\">$name</a></li>";
        return $html;
    }
    
    public function addLink($url, $name)
    {
        $html = "<li class=\"insert\"><a href=\"$url\">$name</a></li>";
        return $html;
    }
    
    public function printLink()
    {
        $html = '<li class="print"><a href="#" onClick="window.print();">Imprimir</a>';
        return $html;
    }
    
    public function defaultHelperLinks($helpUrl = null)
    {
        $html  = $this->helpLink($helpUrl);
        $html .= $this->printLink();
        return $html;
    }
    
    public function helpLink($url)
    {
        $html = "<li class=\"help\"><a href=\"#\" onClick=\"window.open($url);\">Ajuda</a>";
        return $html;
    }
    
    public function closeList()
    {
        $html  = '</ul>';
        return $html;
    }
}