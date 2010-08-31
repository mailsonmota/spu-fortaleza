<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_simpleurl extends Zend_View_Helper_Abstract
{
    protected $_controller;
    protected $_action;
    protected $_params = array();
    
    /**
     * Construtor da classe
     * 
     * Armazena os parâmetros na classe e retorna a url gerada à partir deles.
     * 
     * @param string $action
     * @param string $controller
     * @param array $params
     */
    public function simpleurl($action = 'index', $controller = NULL, $params = array())
    {
        if ($controller == NULL) {
            $controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        }
        
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_params = $params;
        
        return $this->getUrl();
    }
    
    /**
     * Retorna a URL à partir dos parâmetros da classe
     * 
     * @return string
     */
    public function getUrl()
    {
        $url = array();
        $url['controller'] = $this->_controller;
        $url['action'] = $this->_action;
        
        foreach($this->_params as $key => $value) {
            $url[$key] = $value;
        }
        
        return $this->view->url($url, NULL, TRUE);
    }
}