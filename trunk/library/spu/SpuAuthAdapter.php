<?php
Loader::loadEntity('Usuario');
Zend_Loader::loadClass('ZendAuthAdapterAlfresco');
class SpuAuthAdapter extends ZendAuthAdapterAlfresco
{
    protected function getIdentityFromResponse()
    {
        $username = $this->getUsernameFromResponse();
        $ticket = $this->getTicketFromResponse();
        
        $usuario = new Usuario(
            'http://localhost:8080/alfresco/service',
            $ticket
        );
        
        $usuarioDetails = $usuario->getPerson($username);
        $usuarioGroups = $usuario->getGroups($username);
        $identity = array();
        $identity['user'] = $usuarioDetails;
        $identity['user']['groups'] = $usuarioGroups;
        $identity['ticket'] = $ticket;
        $identity['sessionid'] = $this->getSessionIdFromResponse();
        
        return $identity;
    }
}