<?php
Zend_Loader::loadClass('ZendAuthAdapterAlfresco');
class AuthAdapter extends ZendAuthAdapterAlfresco
{
    protected function getIdentityFromResponse()
    {
        $username = $this->getUsername();
        $ticket = $this->getTicket();
        
        $usuarioService = new Spu_Service_Usuario($ticket);
        $usuario = $usuarioService->find($username);
        
        $identity = array();
        $identity['user'] = $usuario;
        $identity['ticket'] = $ticket;
        $identity['sessionid'] = $this->getSessionIdFromResponse();
        
        return $identity;
    }
    
    protected function _getAlfrescoBaseUrl()
    {
        return Spu_Service_Abstract::getAlfrescoUrl();
    }
}