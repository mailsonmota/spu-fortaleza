<?php
/**
 * BaseController: Controlador base para as telas do SGC
 * @author bruno
 * @package SGC
 */
Loader::loadEnumeration('Mensagem.php');
abstract class BaseController extends Zend_Controller_Action
{
    public function getController()
    {
        return $this->getRequest()->getParam('controller');
    }
    
    public function getAction()
    {
        return $this->getRequest()->getParam('action');
    }
    
    /**
     * Passa a mensagem para o helper exibí-la
     * @param $texto
     * @param $tipo
     */
    public function setMessage($texto, $tipo)
    {
        $this->view->message()->setTexto($texto);
        $this->view->message()->setTipo($tipo);
    }
    
    public function setErrorMessage($texto) 
    {
        $this->view->message()->setTextoLiteral($texto);
        $this->view->message()->setTipo('erro');
    }
    
    public function setInfoMessage($texto)
    {
        $this->view->message()->setTextoLiteral($texto);
        $this->view->message()->setTipo('info');
    }
    
    public function preDispatch()
    {
        if (!$this->isAuthorized()) {
            $request = Zend_Controller_Front::getInstance()->getRequest();
            $request->setControllerName('error');
            $request->setActionName('unauthorized');
            $request->setDispatched(false);
        }
    }
    
    public function init()
    {
        $this->view->controller = $this->getController();
        
        if ($this->getRequest()->getParam('method')) {
            // Parametro da URL
            $param = strtoupper($this->getRequest()->getParam('method'));
            
            //Procura na classe de mensagens
            $constante = constant('Mensagem::' . $param);
            $mensagem  = ($constante) ? $constante : $param;
            
            $this->setMessage($mensagem, 'success');
        }
        
        parent::init();
    }
    
    public function isAuthorized()
    {
        $authPlugin = Zend_Controller_Front::getInstance()->getPlugin('AuthPlugin');
        
        $isAuthorized = TRUE;
        $operacaoGrupo = NULL;
        
        if (isset($this->resources)) {
            $operador = new Entity_Operador();
            if (!is_array($this->resources)) {
                if (!$authPlugin->isAllowed($this->resources)) {
                    $operacaoid = $this->resources;
                    $isAuthorized = FALSE;
                }
            } else {
                foreach ($this->resources as $resource) {
                    if (!$authPlugin->isAllowed($resource)) {
                        $operacaoid = $resource;
                        $isAuthorized = FALSE;
                    }
                }
            }
            if (!$isAuthorized) {
                return FALSE;
            }
        }
        
        return TRUE;
    }
}