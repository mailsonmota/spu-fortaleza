<?php
Loader::loadService('StatusService');
class ConsultarController extends BaseController
{
	public function indexAction()
    {
    	$tiposProcesso = array();
    	$listaStatus = array();
    	try {
    		$tiposProcesso = $this->_getListaTiposProcesso();
    		$listaStatus = $this->_getListaStatus();
    	} catch (Exception $e) {
    		$this->setMessageForTheView($e->getMessage());
    	}
    	
    	$this->view->tiposProcesso = $tiposProcesso;
    	$this->view->listaStatus = $listaStatus;
    	$this->view->abaAtiva = 'dadosGerais';
    }
    
    protected function _getListaTiposProcesso()
    {
        $tipoProcessoService = new TipoProcessoService($this->getTicket());
        $tiposProcesso = $tipoProcessoService->getTiposProcesso();
        $listaTiposProcesso = array();
        $listaTiposProcesso = array_merge($listaTiposProcesso, $this->_getOpcaoVazia());
        foreach ($tiposProcesso as $tipoProcesso) {
            $listaTiposProcesso[$tipoProcesso->id] = $tipoProcesso->nome;
        }

        return $listaTiposProcesso;
    }
    
    protected function _getListaStatus()
    {
    	$statusService = new StatusService($this->getTicket());
    	$status = $statusService->listar();
    	$listaStatus = array();
    	$listaStatus = array_merge($listaStatus, $this->_getOpcaoVazia());
    	foreach ($status as $s) {
    		$listaStatus[$s->id] = $s->nome;
    	}
    	
    	return $listaStatus;
    }
    
    protected function _getOpcaoVazia()
    {
    	$opcaoVazia = array();
    	$opcaoVazia[''] = '';
    	return $opcaoVazia;
    }

    public function resultadosAction()
    {
        if (!$this->getRequest()->isPost()) {
            $this->setErrorMessage('Busca inválida.');
            $this->_redirectToConsulta();
        }
        
        $postData = $this->getRequest()->getPost();
        
        if (isset($postData['globalSearch'])) {
            $globalSearch = $postData['globalSearch'];
            $field = $this->_getFieldFromFilter($globalSearch);
            $postData[$field] = $globalSearch;
        }
        
        $processoService = new ProcessoService($this->getTicket());
        
        $processos = $processoService->consultar($postData);
        
        if (count($processos) == 1) {
            $processoId = $processos[0]->id;
            $this->_redirectToProcesso($processoId);
        }
        
        $this->view->processos = $processos;
        $this->view->abaAtiva = 'dadosGerais';
    }
    
    private function _getFieldFromFilter($filter)
    {
        $field = 'any';
        if ($this->_isNumeroProcesso($filter)) {
            $field = 'numero';
        }
        return $field;
    }
    
    private function _isNumeroProcesso($filter)
    {
        // Modelo: AP0712095609/2010
        return (strlen($filter) ==  17);
    }
    
    private function _redirectToConsulta()
    {
        $this->_helper->redirector('index');
    }
    
    private function _redirectToProcesso($processoId)
    {
        $this->_helper->redirector('detalhes', 'processo', 'default', array('id' => $processoId));
    }
    
    public function anexosAction()
    {
    	$this->view->abaAtiva = 'anexos';
    }
    
    public function anexoResultadosAction()
    {
    	$this->view->conteudo = $this->_getParam('conteudo');
    	$this->view->abaAtiva = 'anexos';
    }
    
    private function _redirectToConsultaAnexos()
    {
    	$this->_helper->redirector('anexos');
    }
    
    public function serviceAnexoResultadosAction()
    {
    	
    }
}