<?php
/**
 * BaseController: Controlador base para as telas do SGC
 * @author bruno
 * @package SGC
 */
Loader::loadEnumeration('Mensagem');
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
    public function setMessageForTheView($texto, $tipo = 'info')
    {
        $this->view->message()->setTexto($texto);
        $this->view->message()->setTipo($tipo);
    }
    
    public function setMessage($texto)
    {
        $this->_helper->flashMessenger($texto);
    }
    
    public function setSuccessMessage($texto)
    {
        $this->_helper->flashMessenger(array('success' => $texto));
    }
    
    public function setErrorMessage($texto) 
    {
        $this->_helper->flashMessenger(array('error' => $texto));
    }
    
    public function init()
    {
        $this->view->controller = $this->getController();
        /*$identity = AuthPlugin::getIdentity();
        $this->view->user = $identity['user'];*/
        
        $authInstance = Zend_Auth::getInstance()->getIdentity();
        $this->view->pessoa = $authInstance['user'];
        
        $this->setMessageFromFlashMessenger();
        $this->setMessageFromUrl();
        
        parent::init();
    }
    
    private function setMessageFromFlashMessenger()
    {
        if ($this->_helper->flashMessenger->getMessages()) {
            $messages = $this->_helper->flashMessenger->getMessages();
            $message = $messages[0];
            $type = key($message);
            $this->setMessageForTheView($message[$type], $type);
        }
    }
    
    private function setMessageFromUrl()
    {
        if ($this->getRequest()->getParam('method')) {
            // Parametro da URL
            $param = strtoupper($this->getRequest()->getParam('method'));
            
            //Procura na classe de mensagens
            $constante = constant('Mensagem::' . $param);
            $mensagem  = ($constante) ? $constante : $param;
            
            $this->setMessageForTheView($mensagem, 'success');
        }
    }
    
    protected function getTicket()
    {
        $user = AuthPlugin::getIdentity();
        return $user['ticket'];
    }
    
    protected function getAdminTicket()
    {
    	$authNamespace = new Zend_Session_Namespace('Zend_Auth');
    	return $authNamespace->adminTicket;
    }
}