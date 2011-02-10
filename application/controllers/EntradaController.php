<?php
require_once('BaseTramitacaoController.php');
class EntradaController extends BaseTramitacaoController
{
	public function indexAction() {}
    
    public function receberAction()
    {
    	try {
    		if (!$this->getRequest()->isPost() OR !$this->getRequest()->getParam('processos')) {
    			throw new Exception("Por favor, selecione pelo menos um processo para receber.");
    		}
    		$processoService = new ProcessoService($this->getTicket());
    		$processoService->receberVarios($this->getRequest()->getPost());
    		$this->setSuccessMessage('Processos recebidos com sucesso.');
    	} catch (Exception $e) {
    		$this->setErrorMessage($e->getMessage());
    		$this->_redirectEntrada();
    	}
    	$this->_redirectEmAnalise();
    }
}