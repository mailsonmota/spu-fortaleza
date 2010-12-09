<?php
require_once('BaseTramitacaoController.php');
class SaidaController extends BaseTramitacaoController
{
	public function indexAction()
    {
    	if ($this->getRequest()->isPost()) {
    		try {
    			$processo = new Processo($this->getTicket());
	    		$processo->cancelarEnvios($this->getRequest()->getPost());
	    		$this->setSuccessMessage('Envios cancelados com sucesso.');
	    		$this->_redirectEntrada();
			} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    	
        $processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaSaida();
    }
}