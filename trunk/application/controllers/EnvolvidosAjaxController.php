<?php
require_once 'BaseDataTablesController.php';
Loader::loadService('ManifestanteService');
class EnvolvidosAjaxController extends BaseDataTablesController
{
	public function listarAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_total = 1000;
		$this->view->manifestantes = $this->_getManifestantes();
	}
	
	protected function _getManifestantes()
	{
		$manifestanteService = new ManifestanteService($this->getTicket());
		return $manifestanteService->getManifestantes($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
	}
	
	public function envolvidoAction()
	{
		$this->_helper->layout()->disableLayout();
		
		$this->view->manifestante = $this->_getManifestante($this->_getSearchTerm());
	}
	
	protected function _getManifestante($cpfCnpj)
	{
		$manifestanteService = new ManifestanteService($this->getTicket());
		return $manifestanteService->getManifestante($cpfCnpj);
	}
	
	protected function _getSearchTerm()
    {
        return $this->getRequest()->getParam('term', null);
    }
}