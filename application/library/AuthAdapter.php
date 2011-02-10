<?php
Loader::loadDao('UsuarioDao');
Zend_Loader::loadClass('ZendAuthAdapterAlfresco');
class AuthAdapter extends ZendAuthAdapterAlfresco
{
    protected function getIdentityFromResponse()
    {
        $username = $this->getUsername();
        $ticket = $this->getTicket();
        
        $usuarioDao = new UsuarioDao($ticket);
        $usuario = $usuarioDao->find($username);
        
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