<?php
/**
 * Controlador base para os controladores que exigem autenticação
 *
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Zend_Controller_Action
 */
abstract class BaseAuthenticatedController extends Zend_Controller_Action
{
    protected function getTicket()
    {
        $user = Plugin_Auth::getIdentity();
        return $user['ticket'];
    }

    protected function getUser()
    {
        $user = Plugin_Auth::getIdentity();
        return $user['user'];
    }

    protected function _validateAuthInstance($authInstance = null)
    {
        if (!isset($authInstance)) {
            throw new Exception("Por favor, autentique-se no sistema.");
        } elseif (!key_exists('user', $authInstance)) {
            throw new Exception("Sua sessão está corrompida. Por favor, autentique-se novamente.");
        } else {
            $alfrescoLogin = new Alfresco_Rest_Login(Spu_Service_Abstract::getAlfrescoUrl());
            $alfrescoLogin->setTicket($this->getTicket());
            if (!$alfrescoLogin->validate()) {
                throw new Exception("Sua sessão expirou. Por favor, autentique-se novamente.");
            }
        }
    }
    
    public function getExcludeNodeRed()
    {
        return Zend_Registry::get('excludeNodeRefs');
    }
    
    protected function _getListaOrigens()
    {
        $protocoloService = new Spu_Service_Protocolo($this->getTicket());
        $protocolos = $protocoloService->getProtocolos();
        $listaProtocolos = array();
        foreach ($protocolos as $protocolo) {
            $listaProtocolos[$protocolo->id] = $protocolo->path;
        }

        if (count($listaProtocolos) == 0) {
            throw new Exception(
                'Você não pode enviar nenhum processo, pois não possui acesso à nenhum protocolo.'
            );
        }

        return $listaProtocolos;
    }
}