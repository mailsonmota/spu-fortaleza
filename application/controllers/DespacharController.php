<?php
require_once('BaseTramitacaoController.php');
class DespacharController extends BaseTramitacaoController
{
	public function indexAction()
    {
    	if ($this->getRequest()->isPost()) {
    		try {
    			$processo = new Processo($this->getTicket());
	    		$processo->comentarVarios($this->getRequest()->getPost());
	    		$this->setSuccessMessage('Despachos criados com sucesso.');
	    		$this->_redirectEmAnalise();
			} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    	
    	$processos = array();
    	
	    try {
	    	$session = new Zend_Session_Namespace('comentar');
	    	$processosSelecionados = $session->processos;
	    	$processos = $this->_getListaCarregadaProcessos($processosSelecionados);
	    } catch (Exception $e) {
    		$this->setErrorMessage($e->getMessage());
    		$this->_redirectEmAnalise();
    	}
    	
        $this->view->processos = $processos;
    }
}