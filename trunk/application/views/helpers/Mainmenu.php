<?php
class Zend_View_Helper_Mainmenu extends Zend_View_Helper_Abstract
{
    private $_items = array();
    private $_autoOrder = FALSE;
    private $_options = array();
    
    public function mainmenu()
    {
        return $this;
    }
    
    public function setAutoOrder($value)
    {
        $this->_autoOrder = $value;
    }
    
    public function setShowLogout($value)
    {
        $this->_showLogout = $value;
    }
    
    public function addItem($name, $url, $group = NULL, $resource = NULL, array $options = array())
    {
    	$this->_items[$group][] = array('name' => $name, 'url' => $url, 'resource' => $resource, 'options' => $options);
        
        return $this;
    }
    
    public function orderItems()
    {
        if (!function_exists('compareItems')) {
            function compareItems($itemA, $itemB) {
                return strnatcasecmp($itemA['name'], $itemB['name']);
            }
        }
        
        foreach ($this->_items as $key=>$value) {
            uasort($this->_items[$key], 'compareItems');
        }
    }
    
    public function render()
    {
        if ($this->_autoOrder) {
            $this->orderItems();
        }
        
        $html = '<ul>';
        foreach ($this->_items as $key => $value) {
            if ($key) {
                $html .= "<li><span class=\"{$this->getSpanClass($key)}\">$key</span><ul>";
            }
            
            foreach ($this->_items[$key] as $item) {
                $target = $this->_getTarget($item['options']);
                $targetHtml = ($target) ? "target=\"$target\"" : '';
                $html .= "<li><a href=\"{$item['url']}\" $targetHtml>{$item['name']}</a></li>";
            }
            
            if ($key) {
                $html .= '</ul></li>';
            }
        }
        $html .= '</ul>';
        
        return $html;
    }
    
    protected function getSpanClass($key) {
        return strtr(strtolower($key), array('á'=>'a'));
    }
    
    protected function _getTarget($options)
    {
    	return (isset($options['target'])) ? $options['target'] : null; 
    }
}