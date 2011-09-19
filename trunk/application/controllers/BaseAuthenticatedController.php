<?php
class BaseAuthenticatedController extends Zend_Controller_Action
{
    protected function getTicket()
    {
        $user = Plugin_Auth::getIdentity();
        return $user['ticket'];
    }
    
    protected function _validateAuthInstance($authInstance = null)
    {
        if (!isset($authInstance)) {
            throw new Exception("Por favor, autentique-se no sistema.");
        } elseif (!key_exists('user', $authInstance)) {
            throw new Exception("Sua sessão está corrompida. Por favor, autentique-se novamente.");
        } else {
            $alfrescoLogin = new Alfresco_Rest_Login(Spu_Service_Abstract::getBaseUrl());
            $alfrescoLogin->setTicket($this->getTicket());
            if (!$alfrescoLogin->validate()) {
                throw new Exception("Sua sessão expirou. Por favor, autentique-se novamente.");
            }
        }
    }
}