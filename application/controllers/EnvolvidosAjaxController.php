<?php
require_once 'BaseDataTablesController.php';
Loader::loadService('ManifestanteService');
class ProtocolosAjaxController extends BaseDataTablesController
{
	public function listarAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_total = 1000;
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