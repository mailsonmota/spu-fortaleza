<?php
/**
 * Zend Auth Adapter Alfresco
 *
 * Adapter to make the authentication through Alfresco <http://www.alfresco.com/> webservice.
 *
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @version 0.1
*/

/**
 * @see Zend_Auth_Adapter_Interface
 */
class ZendAuthAdapterAlfresco implements Zend_Auth_Adapter_Interface
{
    protected $_username;
    protected $_password;
    protected $_wsdlAddress;
    protected $_response;
    protected $_result;
    
    /**
     * Sets username and password for authentication
     *
     * @return void
     */
    public function __construct($username, $password, $wsdlAddress)
    {
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setWsdlAddress($wsdlAddress);
        $this->setDefaultResult();
    }
    
    protected function setDefaultResult()
    {
        $this->_result = array('code' => null, 'messages' => array(), 'identity' => null);
    }
 
    protected function getUsername()
    {
        return $this->_username;
    }
    
    protected function setUsername($username)
    {
        $this->_username = $username;
    }
    
    protected function getPassword()
    {
        return $this->_password;
    }
    
    protected function setPassword($password)
    {
        $this->_password = $password;
    }
    
    protected function getWsdlAddress()
    {
        return $this->_wsdlAddress;
    }
    
    protected function setWsdlAddress($wsdlAddress)
    {
        $this->_wsdlAddress = $wsdlAddress;
    }
    
    protected function getResponse()
    {
        return $this->_response;
    }
    
    protected function setResponse($response)
    {
        $this->_response = $response;
    }
    
    protected function getResultCode()
    {
        return $this->_result['code'];
    }
    
    protected function setResultCode($code)
    {
        $this->_result['code'] = $code;
    }
    
    protected function getResultMessages()
    {
        return $this->_result['messages'];
    }
    
    protected function addResultMessage($message)
    {
        $this->_result['messages'][] = $message;
    }
    
    protected function getResultIdentity()
    {
        return $this->_result['identity'];
    }
    
    protected function setResultIdentity($identity)
    {
        $this->_result['identity'] = $identity;
    }
    
    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        try {
            $this->authenticateOnAlfresco();
            $this->setApplicationUser();
        } catch (Exception $e) {
            $this->throwAuthorizationError($e);
        }
        $code = $this->getResultCode();
        $identity = $this->getResultIdentity();
        $messages = $this->getResultMessages();
        return new Zend_Auth_Result($code, $identity, $messages);
    }
    
    protected function authenticateOnAlfresco()
    {
        $username = $this->getUsername();
        $password = $this->getPassword();
        $soapClient = $this->getSoapClient();
        
        $response = $soapClient->startSession(array('username' => $username, 'password' => $password));
        
        $this->setResponse($response);
    }
    
    protected function getSoapClient()
    {
        $soap_client = new Zend_Soap_Client($this->getWsdlAddress());
        return $soap_client;
    }
    
    protected function setApplicationUser()
    {
        $messages = array();
        $code = $this->getSuccessCode();
        $identity = $this->getIdentityFromResponse();
        
        $this->setResultCode($code);
        $this->setResultIdentity($identity);
    }
    
    protected function getSuccessCode()
    {
        return Zend_Auth_Result::SUCCESS;
    }
    
    protected function getIdentityFromResponse()
    {
        $identity = array();
        $identity['username'] = $this->getUsernameFromResponse();
        $identity['ticket'] = $this->getTicketFromResponse();
        $identity['sessionid'] = $this->getSessionIdFromResponse();
        
        return $identity;
    }
    
    protected function getUsernameFromResponse()
    {
        return $this->getResponse()->startSessionReturn->username;
    }
    
    protected function getTicketFromResponse()
    {
        return $this->getResponse()->startSessionReturn->ticket;
    }
    
    protected function getSessionIdFromResponse()
    {
        return $this->getResponse()->startSessionReturn->sessionid;
    }
    
    protected function throwAuthorizationError($exception)
    {
        $code = $this->getDefaultErrorCode();
        if ($this->isConnectionFailure($exception)) {
            $code = $this->getConnectionFailureErrorCode();
        } elseif ($this->isAlfrescoError($exception)) {
            $code = $this->getAlfrescoErrorCode($exception);
        }
        $this->setResultCode($code);
    }
    
    protected function getDefaultErrorCode()
    {
        Zend_Auth_Result::FAILURE_UNCATEGORIZED;
    }
    
    protected function isConnectionFailure($exception)
    {
        return !isset($exception->detail);
    }
    
    protected function getConnectionFailureErrorCode()
    {
        return Zend_Auth_Result::FAILURE;
    }
    
    protected function isAlfrescoError($exception)
    {
        return isset($exception->detail);
    }
    
    protected function getAlfrescoErrorCode($exception)
    {
        $errorCode = $exception->detail->AuthenticationFault->errorCode;
        switch ($errorCode) {
           case 100:
               $code = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
               break;
           default:
               $code = Zend_Auth_Result::FAILURE_UNCATEGORIZED;
               break;
        }
        return $code;
    }
}