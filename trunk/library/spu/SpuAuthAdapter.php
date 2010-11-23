<?php
Loader::loadEntity('Usuario');
Zend_Loader::loadClass('ZendAuthAdapterAlfresco');
class SpuAuthAdapter extends ZendAuthAdapterAlfresco
{
    protected function getIdentityFromResponse()
    {
        //$username = $this->getUsernameFromResponse();
        //$ticket = $this->getTicketFromResponse();
        
    	$username = $this->getUsername();
    	$ticket = $this->getTicket();
    	
        $usuario = new Usuario($ticket);
        $usuario->carregarPeloLogin($username);
        
        $identity = array();
        $identity['user'] = $usuario;
        $identity['ticket'] = $ticket;
        $identity['sessionid'] = $this->getSessionIdFromResponse();
        
        return $identity;
    }
}