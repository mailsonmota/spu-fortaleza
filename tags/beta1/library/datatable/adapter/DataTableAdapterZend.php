<?php
require_once('DataTableAdapterInterface.php');
class DataTableAdapterZend implements DataTableAdapterInterface
{
	public function getPostData()
    {
        return $this->getRequest()->getPost();
    }

	public function hasPostData()
    {
        return $this->getRequest()->isPost();
    }

	public function getCurrentUrl()
    {
        return $this->getRequest()->getRequestUri();
    }
    
    public function getDefaultLinkColumnUrl()
    {
        return $this->getCurrentUrl() . '/editar';    
    }
    
    private function isParamUsuario($param)
    {
        $systemParams = array('controller', 'action', 'module', 'pagina', 'method', $this->keyColumn[0]);
        return !in_array($param, $systemParams); 
    }

	public function getParameters()
    {
        return $this->getRequest->getParam();
    }

    public function getParameter($name)
    {
        return $this->getRequest()->getParam($name);
    }
    
	/**
     * @param unknown_type $parameter
     * @param unknown_type $value
     */
    public function makeUrl($url, array $parameters)
    {
        $url = ($url) ? $url : '';
        foreach ($parameters as $key => $value) {
            if (is_string($key)) {
                $url = $this->addParameterToTheUrl($url, $key);
            }
            if ($value) {
                $url = $this->addParameterToTheUrl($url, $value);
            }
        }
        return $url;
    }
    
    private function addParameterToTheUrl($url, $param) {
        $url .= ($url[strlen($url) - 1] == '/') ? "$param/" : "/$param/";
        return $url;
    }
    
    public function linkColumnActionDefault()
    {        
        $controller = $this->getRequest()->getParam('controller');
            
        $action = 'editar';
        $url = $this->url(array('controller' => $controller, 'action' => $action), NULL, TRUE);
        
        $requestParams = $this->getRequest()->getUserParams();
            
        $params = array();
        foreach ($requestParams as $name=>$value) {
            if ($this->isParamUsuario($name)) {
                $params[$name] = $value;
            }
        }   

        foreach ($params as $name => $value) {
            $url .= '/' . $name . '/'. $value;
        }        
        return $url;
    }

    private function getRequest()
    {
        return Zend_Controller_Front::getInstance()->getRequest();
    }
}