<?php
class Zend_View_Helper_Mainmenu extends Zend_View_Helper_Abstract
{
    private $_items = array();
    private $_autoOrder = FALSE;
    private $_showLogout = TRUE;
    
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
    
    public function addItem($name, $group = NULL, $resource = NULL, 
        $controller = NULL, $action = NULL, $params = array(), 
        $float = NULL)
    {
        $this->_items[$group][] = array(
            'name' => $name, 
            'resource' => $resource, 
            'controller' => $controller, 
            'action' => $action, 
            'params' => $params
        );
        
        return $this;
    }
    
    public function orderItems()
    {
        function compareItems($itemA, $itemB)
        {
            return strnatcasecmp($itemA['name'], $itemB['name']);
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
        
        $html  = '<ul>';
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
                    $html .= '<li>';
                    $html .= '<a>' . $key . '</a>';
                    $html .= '<ul>';
                }
            }
            
            foreach ($this->_items[$key] as $item) {
                if ($this->isAllowed($item)) {
                    $url = $this->view->url(
                        array(
                            'controller' => $item['controller'], 
                            'action' => $item['action'], 
                            'params' => $item['params']
                        ), 
                        NULL, 
                        TRUE
                    );
                    
                    $html .= '<li>';
                    $html .= '<a href="' . $url . '">';
                    $html .= $item['name'];
                    $html .= '</a>';
                    $html .= '</li>';
                }
            }
            
            if ($key AND $acesso) {
                $html .= '</ul>';
                $html .= '</li>';
            }
        }
        
        if ($this->_showLogout) {
            $logoutUrl = $this->view->url(
                array('controller' => 'auth', 'action' => 'logout', 'params' => array()), NULL, TRUE
            );
            
            $html .= '<li style="float: right">
                          <a href="' . $logoutUrl . '">Sair</a>
                      </li>';
            $html .= '</ul>';
        }
        
        return $html;
    }
    
    protected function isAllowed($item)
    {
        if (!$item['resource']) {
            return true;
        } else {
            $authPlugin = Zend_Controller_Front::getInstance()->getPlugin('AuthPlugin');
            
            return $authPlugin->isAllowed($item['resource']);
        }
        
        return false;
    }
    
    protected function _renderMenu(Zend_Navigation_Container $container,
                                   $ulClass,
                                   $indent,
                                   $minDepth,
                                   $maxDepth,
                                   $onlyActive)
    {
        // create iterator
        $iterator = new RecursiveIteratorIterator($container,
                            RecursiveIteratorIterator::SELF_FIRST);
        if (is_int($maxDepth)) {
            $iterator->setMaxDepth($maxDepth);
        }
        
        $authPlugin = Zend_Controller_Front::getInstance()->getPlugin('AuthPlugin');
        
        foreach ($iterator as $page) {
            if (!$authPlugin->isAuthorized($page->getController(), $page->getAction())) {
                $page->setVisible(FALSE);
            }
        }
        
        return parent::_renderMenu($container, $ulClass, $indent, $minDepth, $maxDepth, $onlyActive);
    }
}