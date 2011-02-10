<?php
Loader::loadDao('ProtocoloDao');
class ProtocolosController extends BaseController
{
	public function indexAction() {}

	public function editarAction()
	{
		$id = $this->_getIdFromUrl();

		$protocoloDao = new ProtocoloDao($this->getTicket());
		$protocolo = $protocoloDao->getProtocolo($id);
		
		$this->view->protocolo = $protocolo;
		$this->view->id = $protocolo->getId();
	}

	private function _getIdFromUrl()
	{
		$id = $this->getRequest()->getParam('id');
		return $id;
	}
}