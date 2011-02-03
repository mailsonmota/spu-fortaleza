<?php
Loader::loadEntity('Assunto');
class AssuntosController extends BaseController
{
	public function indexAction()
	{
		$assunto = new Assunto($this->getTicket());
		$listaAssuntos = $assunto->listar();
		$this->view->lista = $listaAssuntos;
	}

	public function editarAction()
	{
		$id = $this->_getIdFromUrl();

		$assunto = new Assunto($this->getTicket());
		$assunto->carregarPeloId($id);

		$this->view->assunto = $assunto;
		$this->view->id = $assunto->getId();
		$this->view->isEdit = true;
	}

	private function _getIdFromUrl()
	{
		$id = $this->getRequest()->getParam('id');
		return $id;
	}
	
	public function formularioAction()
	{
		$id = $this->_getIdFromUrl();

		$assunto = new Assunto($this->getTicket());
		$assunto->carregarPeloId($id);

		$this->view->assunto = $assunto;
		$this->view->id = $assunto->getId();
		$this->view->isEdit = true;
		$this->view->result = $assunto->getFormularioXsd();
	}
}