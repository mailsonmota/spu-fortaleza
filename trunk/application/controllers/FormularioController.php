<?php
Loader::loadEntity("Assunto");
class FormularioController extends BaseController
{
	public function contentAction()
	{
		$this->_helper->layout()->disableLayout();
		$id = $this->getRequest()->getParam('id');
		$assunto = new Assunto($this->getTicket());
		$assunto->carregarPeloId($id);
		$this->view->result = $assunto->getFormularioXsd();;
	}

	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->view->id = $id;
	}
}
