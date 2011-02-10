<?php
Loader::loadEntity("Assunto");
class FormularioController extends BaseController
{
	public function contentAction()
	{
		$this->_helper->layout()->disableLayout();
		$id = $this->getRequest()->getParam('id');
		$arquivoDao = new ArquivoDao($this->getTicket());
		$this->view->result = $arquivoDao->getContentFromUrl(array('id' => $id));
	}

	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->view->id = $id;
	}
}
