<?php
require_once('BaseTramitacaoController.php');
class EncaminharController extends BaseTramitacaoController
{
	public function indexAction()
    {
    	if ($this->getRequest()->isPost()) {
    		try {
    			$processo = new Processo($this->getTicket());
	    		$processo->tramitarVarios($this->getRequest()->getPost());
	    		$this->setSuccessMessage('Processos tramitados com sucesso.');
	    		$this->_redirectEmAnalise();
			} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    	
    	$processos = array();
    	$listaProtocolos = array();
    	
	    try {
	    	$session = new Zend_Session_Namespace('encaminhar');
	    	$processosSelecionados = $session->processos;
	    	$processos = $this->_getListaCarregadaProcessos($processosSelecionados);
	    	$listaProtocolos = $this->_getListaProtocolos();
	    	$protocolo = new Protocolo($this->getTicket());
	    	$protocolos = $protocolo->listarTodos();
	    	
	    } catch (Exception $e) {
    		$this->setErrorMessage($e->getMessage());
    		$this->_redirectEmAnalise();
    	}
    	
        $this->view->processos = $processos;
        $this->view->listaProtocolos = $listaProtocolos;
        $this->view->protocolos = $protocolos;
    }
    
	protected function _getListaProtocolos()
    {
        $protocolo = new Protocolo($this->getTicket());
        $protocolos = $protocolo->listarTodos();
        $listaProtocolos = array();
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
    
    protected function _getListaCarregadaProcessos($listaComIdsProcessos)
    {
    	$processos = array();
    	foreach ($listaComIdsProcessos as $processoId) {
        	$processo = new Processo($this->getTicket());
        	$processo->carregarPeloId($processoId);
        	$processos[] = $processo;
        }
        
        return $processos;
    }
    
    public function externoAction()
    {
    	if ($this->getRequest()->isPost()) {
    		try {
    			$processo = new Processo($this->getTicket());
	    		$processo->tramitarExternos($this->getRequest()->getPost());
	    		$this->setSuccessMessage('Processos tramitados com sucesso.');
	    		$this->_redirectEmAnalise();
			} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    	
    	$processos = array();
    	
	    try {
	    	$session = new Zend_Session_Namespace('encaminharExternos');
	    	$processosSelecionados = $session->processos;
	    	$processos = $this->_getListaCarregadaProcessos($processosSelecionados);
	    } catch (Exception $e) {
    		$this->setErrorMessage($e->getMessage());
    		$this->_redirectEmAnalise();
    	}
    	
        $this->view->processos = $processos;
    }
}