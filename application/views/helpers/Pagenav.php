<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_Pagenav extends Zend_View_Helper_Abstract
{
    const BASE_HELP_URL = '../docs/';
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
        $html = '<li class="print"><a href="#" onclick="window.print();">Imprimir</a></li>';
        return $html;
    }
    
    public function defaultHelperLinks($helpUrl = null)
    {
        $html  = $this->helpLink($this->_getHelpLinkUrl($helpUrl));
        $html .= $this->printLink();
        return $html;
    }
    
    protected function _getHelpLinkUrl($url = null)
    {
        return ($url) ? Zend_Controller_Front::getInstance()->getBaseUrl() . '/' . self::BASE_HELP_URL . $url : null;
    }
    
    public function helpLink($url)
    {
        $html = ($url) ? "<li class=\"help\"><a href=\"$url\" rel=\"facebox\">Ajuda</a></li>" : "";
        return $html;
    }
    
    public function closeList()
    {
        $html  = '</ul>';
        return $html;
    }
}