<?php
/**
 * AuthPlugin - Plugin de Autorização do SPU para o Zend Framework
 * @author bruno
 * @package SPU
 */
class AuthPlugin extends Zend_Controller_Plugin_Abstract
{
    private $_auth;
    
    //Ausencia de autenticacao redirecinar para:
    const FAIL_AUTH_MODULE     = '';
    const FAIL_AUTH_CONTROLLER = 'auth';
    const FAIL_AUTH_ACTION     = 'login';
    
    //Ausencia de autorizacao redirecinar para:
    const FAIL_ACL_MODULE     = '';
    const FAIL_ACL_CONTROLLER = 'error';
    const FAIL_ACL_ACTION     = 'unauthorized';
    
    public function __construct(Zend_Auth $auth) 
    {
        //Inicializando ACL e AuthPlugin
        $this->_auth  = $auth;
    }
    
    public function preDispatch(Zend_Controller_Request_Abstract $request) 
    {
        //Variaveis de request
        $module     = $request->getModuleName();
        $controller = $request->getControllerName();
        $action     = $request->getActionName();
        
        // Usuário Logado
        if ((!$this->_isIdentityValid() OR !$this->_isValidTicket()) AND $controller != 'auth') {
            Zend_Auth::getInstance()->clearIdentity();
            $module = self::FAIL_AUTH_MODULE;
            $controller = self::FAIL_AUTH_CONTROLLER;
            $action = self::FAIL_AUTH_ACTION;
        }
        
        $request->setModuleName($module);
        $request->setControllerName($controller);
        $request->setActionName($action);
    }
    
    protected function _isValidTicket()
    {
    	Loader::loadAlfrescoApiClass('Login');
        
        $alfrescoLogin = new Alfresco_Rest_Login(BaseDao::ALFRESCO_URL);
        $user = $this->getIdentity();
        $alfrescoLogin->setTicket($user['ticket']);
        
        return $alfrescoLogin->validate();
    }
    
    protected function _isIdentityValid()
    {
        $isValid = false;
        if ($this->_auth->hasIdentity()) {
            $data = $this->getIdentity();
            if (!is_string($data)) {
                $isValid = true;
            }
        }
        
        return $isValid;
    }
    
    /**
     * Retorna o usuário logado
     * @return Entity_Operador
     */
    public static function getIdentity()
    {
        return Zend_Auth::getInstance()->getIdentity();
    }
}