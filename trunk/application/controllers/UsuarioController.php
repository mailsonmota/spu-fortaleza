<?php
class UsuarioController extends BaseController
{
    public function cadastroAction() {
    	$this->view->nomeProtocolos = $this->_getNomeProtocolosUsuario();
    }
    
    protected function _getNomeProtocolosUsuario()
    {
    	$service = new Spu_Service_Protocolo($this->getTicket());
    	$nomeProtocolos = array();
    	foreach ($service->getProtocolos() as $p) {
    		$nomeProtocolos[] = $p->path;
    	}
    	
    	return $nomeProtocolos;
    }
}