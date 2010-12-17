<?php
Loader::loadEntity('Protocolo');
class ProtocolosController extends BaseController
{
	public function indexAction()
	{
		$protocolo = new Protocolo($this->getTicket());
		$listaProtocolos = $protocolo->listarTodos();
		$this->view->lista = $listaProtocolos;
	}

	public function editarAction()
	{
		$id = $this->_getIdFromUrl();

		$protocolo = new Protocolo($this->getTicket());
		$protocolo->carregarPeloId($id);

		if ($this->getRequest()->isPost()) {
			$postData = $this->getRequest()->getPost();
			if (!isset($postData['recebeMalotes'])) {
				$postData['recebeMalotes'] = 0;
			}
			if (!isset($postData['recebePelosSubsetores'])) {
				$postData['recebePelosSubsetores'] = 0;
			}
			$protocolo->alterar($postData);
			$this->setSuccessMessage("Protocolo alterado com sucesso");
		}

		$this->view->protocolo = $protocolo;
		$this->view->id = $protocolo->getId();
	}

	private function _getIdFromUrl()
	{
		$id = $this->getRequest()->getParam('id');
		return $id;
	}
}