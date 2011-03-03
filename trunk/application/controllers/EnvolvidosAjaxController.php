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
	
	public function autocompleteAction()
	{
		$this->_helper->layout()->disableLayout();

		$manifestanteService = new ManifestanteService($this->getTicket());
	}
	
	protected function _getSearchTerm()
    {
        return $this->getRequest()->getParam('term', null);
    }
}