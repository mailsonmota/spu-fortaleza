<?php
Loader::loadService('StatusService');
class ConsultarController extends BaseController
{
    public function indexAction()
    {
    	$tiposProcesso = array();
    	try {
    		$tiposProcesso = $this->_getListaTiposProcesso();
    	} catch (Exception $e) {
    		$this->setMessageForTheView($e->getMessage());
    	}
    	
    	$this->view->tiposProcesso = $tiposProcesso;
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
    	$opcaoVazia[0] = '';
    	return $opcaoVazia;
    }

    public function executarAction()
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
        $resultado = $processoService->consultar($postData);
        
        if (count($resultado) == 1) {
            $processoId = $resultado[0]->id;
            $this->_redirectToProcesso($processoId);
        } else {
            echo '<pre>'; var_dump($resultado); echo '</pre>'; exit;
        }
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
}