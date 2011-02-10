<?php
Loader::loadService('UsuarioService');
Zend_Loader::loadClass('ZendAuthAdapterAlfresco');
class AuthAdapter extends ZendAuthAdapterAlfresco
{
    protected function getIdentityFromResponse()
    {
        $username = $this->getUsername();
        $ticket = $this->getTicket();
        
        $usuarioService = new UsuarioService($ticket);
        $usuario = $usuarioService->find($username);
        
        $identity = array();
        $identity['user'] = $usuario;
        $identity['ticket'] = $ticket;
        $identity['sessionid'] = $this->getSessionIdFromResponse();
        
        return $identity;
    }
    
    protected function _getAlfrescoBaseUrl()
    {
        return BaseDao::ALFRESCO_URL;
    }
}