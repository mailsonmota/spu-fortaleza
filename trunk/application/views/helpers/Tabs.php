<?php
class Zend_View_Helper_Tabs extends Zend_View_Helper_Abstract
{
    const LIST_CLASS = 'tabs';
    
    protected $_options = array();
    
    public function tabs()
    {
        return $this;
    }
    
    public function openList()
    {
        return '<ul class="' . self::LIST_CLASS . '">';
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
        return '</ul>';
    }
}
