<?php
Loader::loadEntity('Protocolo');
class ProtocolosController extends BaseController
{
	public function indexAction() 
	{
		$this->view->ticket = $this->getTicket();
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
			$this->setMessageForTheView("Protocolo alterado com sucesso", 'sucess');
		}

		$listaProtocolos = array();
		 
		try {
			$listaProtocolos = $this->_getListaProtocolos();
		} catch (Exception $e) {
			$this->setMessageForTheView($e->getMessage(), 'error');
		}

		$this->view->protocolo = $protocolo;
		$this->view->listaProtocolos = $listaProtocolos;
		$this->view->id = $protocolo->getId();
	}

	private function _getIdFromUrl()
	{
		$id = $this->getRequest()->getParam('id');
		return $id;
	}

	protected function _getListaProtocolos()
	{
		$protocolo = new Protocolo($this->getTicket());
		$protocolos = $protocolo->listarTodos();
		$listaProtocolos = array();
		$listaProtocolos[0] = 'Nenhum';
		foreach ($protocolos as $protocolo) {
			$listaProtocolos[$protocolo->id] = $protocolo->descricao;
		}

		if (count($listaProtocolos) == 0) {
			throw new Exception(
                'Não existe nenhum protocolo cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
                );
		}

		return $listaProtocolos;
	}
}