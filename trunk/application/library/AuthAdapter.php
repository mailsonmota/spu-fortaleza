<?php
Loader::loadEntity('Usuario');
Zend_Loader::loadClass('ZendAuthAdapterAlfresco');
class AuthAdapter extends ZendAuthAdapterAlfresco
{
    protected function getIdentityFromResponse()
    {
        $username = $this->getUsername();
        $ticket = $this->getTicket();
        
        $usuario = new Usuario($ticket);
        $usuario->carregarPeloLogin($username);
        $usuario->loadGrupos();
        
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