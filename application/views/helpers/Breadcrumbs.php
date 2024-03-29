<?php
/**
 * Breadcrumbs: Helper para gerar os breadcrumbs à partir do navigation.xml
 * @author bruno
 * @package ZendCTI
 */
class Zend_View_Helper_Breadcrumbs extends Zend_View_Helper_Abstract
{
    const SEPARATOR = " &rarr; ";
    
    protected $_page = array(array('nome' => 'Início', 'controller' => '', 'action' => '', 'params' => array()));
    protected $_html;
    
    function addPage($nome, $controler = NULL, $action = NULL, $params = array())
    {
        $page['nome'] = $nome;
        $page['controller'] = $controler;
        $page['action'] = $action;
        $page['params'] = $params;
        
        $this->_page[] = $page;
        
        return $this;
    }
    
    function breadcrumbs()
    {
        return $this;
    }
    
    function render($renderHome = TRUE)
    {    
        $separator = self::SEPARATOR;
        $html = '<div id="breadcrumbs">';
        
        $start = ($renderHome) ? 0 : 1; 
        for ($i = $start; $i < count($this->_page); $i++) {
            $titulo = $this->_page[$i]['nome'];
            
            if ($i > $start) {
                $html .= $separator;
            }
            if ($i+1 < count($this->_page)) {
                $url = array();
                $url['controller'] = $this->_page[$i]['controller'];
                $url['action'] = $this->_page[$i]['action'];
                
                foreach ($this->_page[$i]['params'] as $key => $value) {
                    $url[$key] = $value;
                }
                
                $href = $this->view->url($url, NULL, TRUE);
                $html .= "<a href='$href' title='$titulo'>$titulo</a>";
            } else {
                $html .= "</div>";
                $html .= "<h2>$titulo</h2>";    
            }
        }
        
        $this->_html = $html; 
             
        return $this;
    }
    
    public function __toString()
    {
        return $this->_html;
    }
}