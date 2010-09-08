<?php
Loader::loadAlfrescoObject('Usuario');
Zend_Loader::loadClass('ZendAuthAdapterAlfresco');
class SpuAuthAdapter extends ZendAuthAdapterAlfresco
{
    protected function getIdentityFromResponse()
    {
        $username = $this->getUsernameFromResponse();
        $ticket = $this->getTicketFromResponse();
        $usuario = new Usuario($ticket);
        $usuario->loadUser($username);
        
        $identity = array();
        $identity['user'] = $usuario;
        $identity['ticket'] = $ticket;
        $identity['sessionid'] = $this->getSessionIdFromResponse();
        
        return $identity;
    }
}