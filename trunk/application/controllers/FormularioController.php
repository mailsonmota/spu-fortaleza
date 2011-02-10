<?php
Loader::loadEntity("Assunto");
class FormularioController extends BaseController
{
	public function contentAction()
	{
		$this->_helper->layout()->disableLayout();
		$id = $this->getRequest()->getParam('id');
		$arquivoService = new ArquivoService($this->getTicket());
		$this->view->result = $arquivoService->getContentFromUrl(array('id' => $id));
	}

	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->view->id = $id;
	}
}
