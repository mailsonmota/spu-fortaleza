<?php
require_once('BaseTramitacaoController.php');
Loader::loadService('CopiaProcessoService');
class CopiasController extends BaseTramitacaoController
{
	public function indexAction()
    {
    	if ($this->getRequest()->isPost()) {
    		try {
    			$copiaProcessoService = new CopiaProcessoService($this->getTicket());
    			$copiaProcessoService->excluirTodos($this->getRequest()->getPost());
    			$this->setMessageForTheView('Cópias excluídas com sucesso.', 'success');
    		} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    }
}