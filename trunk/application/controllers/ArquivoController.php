<?php
require_once('BaseTramitacaoController.php');
Loader::loadDao('StatusArquivamentoDao');
class ArquivoController extends BaseTramitacaoController
{
	public function indexAction()
    {
    	if ($this->getRequest()->isPost()) {
    		try {
    			$processosSelecionados = $this->getRequest()->getParam('processos');
        		$session = new Zend_Session_Namespace('reabrir');
        		$session->processos = $processosSelecionados;
        		$this->_redirectReabrir();
    		} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    }
    
    protected function _redirectReabrir()
    {
    	$this->_helper->redirector('reabrir', $this->getController(), 'default');
    }
    
	public function arquivarAction()
    {
    	if ($this->getRequest()->isPost()) {
    		try {
    			$processoDao = new ProcessoDao($this->getTicket());
	    		$processoDao->arquivarVarios($this->getRequest()->getPost());
	    		$this->setSuccessMessage('Processos arquivados com sucesso.');
	    		$this->_redirectArquivo();
			} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    	
    	$processos = array();
    	
	    try {
	    	$session = new Zend_Session_Namespace('arquivar');
	    	$processosSelecionados = $session->processos;
	    	$processos = $this->_getListaCarregadaProcessos($processosSelecionados);
	    	$listaStatusArquivamento = $this->_getListaStatusArquivamento();
	    } catch (Exception $e) {
    		$this->setErrorMessage($e->getMessage());
    		$this->_redirectEmAnalise();
    	}
    	
    	$this->view->processos = $processos;
    	$this->view->listaStatusArquivamento = $listaStatusArquivamento;
    }
    
    protected function _getListaStatusArquivamento()
    {
    	$statusArquivamentoDao = new StatusArquivamentoDao($this->getTicket());
        $opcoes = $statusArquivamentoDao->fetchAll();
        $listaStatusArquivamento = array();
        foreach ($opcoes as $opcao) {
            $listaStatusArquivamento[$opcao->id] = $opcao->descricao;
        }
        
        if (count($listaStatusArquivamento) == 0) {
            throw new Exception(
                'Não existe nenhum status de arquivamento cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaStatusArquivamento;
    }
    
	public function reabrirAction()
    {
    	if ($this->getRequest()->isPost()) {
    		try {
    			$processoDao = new ProcessoDao($this->getTicket());
	    		$processoDao->reabrirVarios($this->getRequest()->getPost());
	    		$this->setSuccessMessage('Processos reabertos com sucesso.');
	    		$this->_redirectEmAnalise();
			} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    	
    	$processos = array();
    	
	    try {
	    	$session = new Zend_Session_Namespace('reabrir');
	    	$processosSelecionados = $session->processos;
	    	$processos = $this->_getListaCarregadaProcessos($processosSelecionados);
	    } catch (Exception $e) {
    		$this->setErrorMessage($e->getMessage());
    		$this->_redirectArquivo();
    	}
    	
        $this->view->processos = $processos;
    }
}