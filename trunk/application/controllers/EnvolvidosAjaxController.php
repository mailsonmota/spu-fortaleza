<?php
class EnvolvidosAjaxController extends BaseAuthenticatedController
{
	public function envolvidoAction()
	{
		$this->_helper->layout()->disableLayout();
		
		$this->view->manifestante = $this->_getManifestante($this->_getSearchTerm());
	}
	
	protected function _getManifestante($cpfCnpj)
	{
		$manifestanteService = new Spu_Service_Manifestante($this->getTicket());
		return $manifestanteService->getManifestante($cpfCnpj);
	}
	
	protected function _getSearchTerm()
    {
        return $this->getRequest()->getParam('term', null);
    }
}