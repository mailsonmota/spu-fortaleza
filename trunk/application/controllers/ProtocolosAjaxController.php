<?php
require_once 'BaseDataTablesController.php';
Loader::loadService('ProtocoloService');
class ProtocolosAjaxController extends BaseDataTablesController
{
    public function listarTodosAction()
    {
        $this->_rows = $this->_getTodosProtocolos();
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->protocolos = $this->_getTodosProtocolos();
    }
    
    protected function _getTodosProtocolos()
    {
        $service = new ProtocoloService($this->getTicket());
        return $service->getTodosProtocolosPaginado($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
    }
    
    public function listarDestinosAction()
    {
        $this->_helper->layout()->disableLayout();
        
        $protocoloOrigemId = $this->_getProtocoloOrigemId();
        $tipoProcessoId = ($this->_getTipoProcessoId()) ? $this->_getTipoProcessoId() : null;
        
        $this->view->resultados = $this->_getListaProtocolosDestino($protocoloOrigemId, $tipoProcessoId);
    }
    
    protected function _getListaProtocolosDestino($protocoloOrigemId, $tipoProcessoId = null, $offset = 0)
    {
        $service = new ProtocoloService($this->getTicket());
        return $service->getProtocolosDestino($protocoloOrigemId,
                                              $tipoProcessoId,
                                              $this->_getSearchTerm(),
                                              $offset,
                                              $this->_getPageSize());
    }
    
    protected function _getSearchTerm()
    {
        return $this->getRequest()->getParam('term', null);
    }

    protected function _getProtocoloOrigemId()
    {
        return $this->getRequest()->getParam('protocoloOrigem', null);
    }
    
    protected function _getTipoProcessoId()
    {
        return $this->getRequest()->getParam('tipoprocesso', null);
    }
    
    public function listarAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$this->view->resultados = $this->_getProtocolosAutocomplete();
    }
    
    protected function _getProtocolosAutocomplete()
    {
        $service = new ProtocoloService($this->getTicket());
        return $service->getTodosProtocolosPaginado($this->_getOffset(), $this->_getPageSize(), $this->_getSearchTerm());
    }
}