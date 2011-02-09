<?php
require_once('BaseTramitacaoController.php');
Loader::loadDao('CopiaProcessoDao');
class CopiasController extends BaseTramitacaoController
{
	public function indexAction()
    {
    	if ($this->getRequest()->isPost()) {
    		try {
    			$copiaProcessoDao = new CopiaProcessoDao($this->getTicket());
    			$copiaProcessoDao->excluirTodos($this->getRequest()->getPost());
    			$this->setMessageForTheView('Cópias excluídas com sucesso.', 'success');
    		} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    }
}