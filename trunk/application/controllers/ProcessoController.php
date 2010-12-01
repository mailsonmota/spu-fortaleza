<?php
Loader::loadEntity('Processo');
class ProcessoController extends BaseController
{
	public function detalhesAction()
    {
    	try {
    		$idProcesso = $this->_getIdProcessoUrl();
	    	$processo = new Processo($this->getTicket());
	        if ($idProcesso) {
	            $processo->carregarPeloId($idProcesso);
	        }
	    } catch (Exception $e) {
    		$this->setMessageForTheView('Não foi possível carregar o processo', 'error');
    	}
    	$this->view->processo = $processo;
    }
	
    public function encaminharAction()
    {
    	try {
    		$idProcesso = $this->_getIdProcessoUrl();
    		$processo = new Processo($this->getTicket());
    		if ($idProcesso) {
	            $processo->carregarPeloId($idProcesso);
	        }
	        
    		$listaPrioridades = $this->_getListaPrioridades();
            $listaProtocolos = $this->_getListaProtocolos();
            
    		if ($this->getRequest()->isPost()) {
    			$processo->tramitar($this->getRequest()->getPost());
    			$this->setSuccessMessage('Processo tramitado com sucesso.');
    			$this->_redirectDetalhesProcesso($idProcesso);
	    	}
    	} catch (Exception $e) {
    		$this->setMessageForTheView($e->getMessage(), 'error');
    	}
    	$this->view->processo = $processo;
    	$this->view->listaPrioridades = $listaPrioridades;
        $this->view->listaProtocolos = $listaProtocolos;
    }
    
    protected function _getIdProcessoUrl()
    {
        $idProcesso = $this->getRequest()->getParam('id');
        return $idProcesso;
    }
    
    protected function _getListaPrioridades()
    {
        $prioridade = new Prioridade($this->getTicket());
        $prioridades = $prioridade->listar();
        $listaPrioridades = array();
        foreach ($prioridades as $prioridade) {
            $listaPrioridades[$prioridade->id] = $prioridade->descricao;
        }
        
        if (count($listaPrioridades) == 0) {
            throw new Exception(
                'Não existe nenhuma prioridade de processo cadastrada no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaPrioridades;
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
    
    protected function _redirectDetalhesProcesso($idProcesso)
    {
    	$this->_helper->redirector('detalhes', $this->getController(), 'default', array('id' => $idProcesso));
    }
}