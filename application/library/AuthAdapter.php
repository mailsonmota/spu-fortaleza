<?php
/** @see ZendAuthAdapterAlfresco */
require_once 'ZendAuthAdapterAlfresco.php';

/**
 * Adaptador do ZendAuthAdapterAlfresco para o SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 */
class AuthAdapter extends ZendAuthAdapterAlfresco
{
    protected function getIdentityFromResponse()
    {
        $ticket = $this->getTicket();
        $usuarioService = new Spu_Service_Usuario($ticket);
        $usuario = $usuarioService->find($this->getUsername());
        
        $identity = array();
        $identity['user'] = $usuario;
        $identity['ticket'] = $ticket;
        $identity['sessionid'] = $this->getSessionIdFromResponse();
        
        return $identity;
    }
    
    /**
     * Retorna a URL do Alfresco
     * 
     * @return string
     * @see ZendAuthAdapterAlfresco::_getAlfrescoBaseUrl()
     */
    protected function _getAlfrescoBaseUrl()
    {
        return Spu_Service_Abstract::getAlfrescoUrl();
    }
}