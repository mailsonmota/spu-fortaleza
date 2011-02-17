<?php
class Zend_View_Helper_Mainmenu extends Zend_View_Helper_Abstract
{
    private $_items = array();
    private $_autoOrder = FALSE;
    
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
        $this->_items[$group][] = array('name' => $name, 'url' => $url, 'resource' => $resource);
        
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
        foreach ($this->_items as $key=>$value) {
            if ($key) {
                // Verifica se o usuario tem acesso à pelo menos um item
                $acesso = FALSE;
                foreach ($this->_items[$key] as $item) {
                    if ($this->isAllowed($item)) {
                        $acesso = TRUE;
                    }
                }
                
                if ($acesso) {
                    $spanClass = $this->getSpanClass($key);
                    $html .= "<li><span class='$spanClass'>$key</span><ul>";
                }
            }
            
            foreach ($this->_items[$key] as $item) {
                if ($this->isAllowed($item)) {
                    $url = $item['url'];
                    $name = $item['name'];
                    $html .= "<li><a href='$url'>$name</a></li>";
                }
            }
            
            if ($key AND $acesso) {
                $html .= '</ul></li>';
            }
        }
        $html .= '</ul>';
        
        return $html;
    }
    
    protected function getSpanClass($key) {
        return strtr(strtolower($key), array('á'=>'a'));
    }
    
    protected function isAllowed($item)
    {
        if (!$item['resource']) {
            return true;
        } else {
            $authPlugin = $this->_getAuthPlugin();
            return $authPlugin->isAllowed($item['resource']);
        }
        
        return false;
    }
    
    protected function _renderMenu(Zend_Navigation_Container $container, $ulClass,
                                   $indent,
                                   $minDepth,
                                   $maxDepth,
                                   $onlyActive)
    {
        // create iterator
        $iterator = new RecursiveIteratorIterator($container, RecursiveIteratorIterator::SELF_FIRST);
        if (is_int($maxDepth)) {
            $iterator->setMaxDepth($maxDepth);
        }
        
        $authPlugin = $this->_getAuthPlugin();
        
        foreach ($iterator as $page) {
            if (!$authPlugin->isAuthorized($page->getController(), $page->getAction())) {
                $page->setVisible(FALSE);
            }
        }
        
        return parent::_renderMenu($container, $ulClass, $indent, $minDepth, $maxDepth, $onlyActive);
    }
    
    private function _getAuthPlugin()
    {
        $authPlugin = Zend_Controller_Front::getInstance()->getPlugin('AuthPlugin');

        return $authPlugin;
    }
}