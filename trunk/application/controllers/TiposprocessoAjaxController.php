<?php
Loader::loadService('TipoProcessoService');
class TiposprocessoAjaxController extends BaseController
{
	public function listarPorProtocoloAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->view->tiposprocesso = $this->_getTiposProcessoPorProtocolo($this->_getProtocoloFromUrl());
	}
	
	protected function _getProtocoloFromUrl()
	{
		return $this->getRequest()->getParam('protocolo', null);
	}
	
	protected function _getTiposProcessoPorProtocolo($protocoloId)
	{
		$tiposProcessoService = new Spu_Service_TipoProcesso($this->getTicket());
		return $tiposProcessoService->getTiposProcesso($protocoloId);
	}
}