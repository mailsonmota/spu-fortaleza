<?php
require_once('BaseTramitacaoController.php');
class EntradaController extends BaseTramitacaoController
{
	public function indexAction()
    {
        $processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaEntrada();
    }
    
    public function receberAction()
    {
    	try {
    		if (!$this->getRequest()->isPost() OR !$this->getRequest()->getParam('processos')) {
    			throw new Exception("Por favor, selecione pelo menos um processo para receber.");
    		}
    		$processo = new Processo($this->getTicket());
    		$processo->receberVarios($this->getRequest()->getPost());
    		$this->setSuccessMessage('Processos recebidos com sucesso.');
    	} catch (Exception $e) {
    		$this->setErrorMessage($e->getMessage());
    		$this->_redirectEntrada();
    	}
    	$this->_redirectEmAnalise();
    }
}