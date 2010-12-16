<?php
require_once('BaseTramitacaoController.php');
Loader::loadEntity('CopiaProcesso');
class CopiasController extends BaseTramitacaoController
{
	public function indexAction()
    {
    	$copiaProcesso = new CopiaProcesso($this->getTicket());
    	
    	if ($this->getRequest()->isPost()) {
    		try {
    			$copiaProcesso->excluir($this->getRequest()->getPost());
    		} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    	
        $this->view->lista = $copiaProcesso->listar();
    }
}