<?php
class AuthController extends BaseController
{
    public function indexAction()
    {
        
    }
    
    public function loginAction()
    {
        $this->_helper->layout()->setLayout('basic');
    }
    
    public function authorizeAction()
    {
        $requestData = $this->getRequest();
        $authAdapter = new SpuAuthAdapter($requestData->getParam('username'), $requestData->getParam('password'));
        
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);
        
        switch ($result->getCode()) {
     
            case Zend_Auth_Result::FAILURE:
                $this->setErrorMessage(
                    'Não foi possível se conectar ao Alfresco. Por favor, tente novamente mais tarde.'
                );
                $this->redirectLogin();
                break;
         
            case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                $this->setErrorMessage('Login/Senha inválidos. Por favor, tente novamente.');
                $this->redirectLogin();
                break;
         
            case Zend_Auth_Result::SUCCESS:
                $this->setSuccessMessage('Login efetuado com sucesso.');
                $this->redirectIndex();
                break;
         
            default:
                $this->setErrorMessage('Ocorreu um erro ao processar sua requisição. Por favor, tente novamente.');
                $this->redirectLogin();
                break;
        }
    }
    
    private function redirectLogin()
    {
        $this->_redirect('login');
    }
    
    private function redirectIndex()
    {
        $this->_redirect(array('action' => 'index', 'controller' => 'index'));
    }
    
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->setSuccessMessage('Logout realizado com sucesso.');
        
        // FIXME substituir
        Loader::loadAlfrescoApiClass('AlfrescoLogin');
        Loader::loadEntity('BaseAlfrescoEntity');

        $alfrescoLoginObj = new AlfrescoLogin(BaseAlfrescoEntity::ALFRESCO_URL);
        $authNamespace = new Zend_Session_Namespace('Zend_Auth');
        $alfrescoLoginObj->logout($authNamespace->adminTicket);
        
        $this->_helper->redirector('login', 'auth');
    }
}