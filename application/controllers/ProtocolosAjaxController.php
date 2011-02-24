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
        $this->view->output = $this->_getOutput();
    }
    
    protected function _getTodosProtocolos()
    {
        $protocoloService = new ProtocoloService($this->getTicket());
        $protocolos = $protocoloService->getTodosProtocolosPaginado($this->_getOffset(),
                                                                    $this->_getPageSize(),
                                                                    $this->_getSearch());
        
        return $this->_convertProtocolosToDataTablesRow($protocolos, true);
    }
    
    protected function _convertProtocolosToDataTablesRow($protocolos, $detalhes = false)
    {
        $rows = array();
        foreach ($protocolos as $protocolo) {
            $row = array();
            $row['nome'] = $protocolo->path;
            
            if ($detalhes) {
                $url = $this->_helper->url('editar', 'protocolos', null, array('id' => $protocolo->id));
                $row['detalhes'] = "<a href='$url'>Detalhes</a>";
            }
            
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function listarDestinosAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->view->output = $this->_getOutputProtocolos($this->_getListaProtocolosDestino());
    }
    
    public function listarDestinosNovoAction()
    {
        $this->_helper->layout()->disableLayout();
        
        $protocoloOrigemId = $this->_getProtocoloOrigemId();
        $tipoProcessoId = ($this->_getTipoProcessoId()) ? $this->_getTipoProcessoId() : null;
        $filter = ($this->_getSearchTerm()) ? $this->_getSearchTerm() : null;
        
        $this->view->output = $this->_getOutputProtocolos($this->_getListaProtocolosDestinoNovo($protocoloOrigemId,
                                                                                                $tipoProcessoId,
                                                                                                $filter));
    }
    
    protected function _getOutputProtocolos($protocolos) {
        $output = '[';
        if (is_array($protocolos)) {
            $i = 0;
            foreach ($protocolos as $protocolo) {
                $output .= (++$i != 1) ? ',' : '';
                $output .= '{
                                "id":"' . $protocolo->id . '", 
                                "label":"' . $protocolo->path . '", 
                                "value":"' . $protocolo->path . '"
                            }';
            }
        }
        $output .= ']';

        return $output;
    }
    
    protected function _getListaProtocolosDestino()
    {
        $protocoloService = new ProtocoloService($this->getTicket());
        $protocolos = $protocoloService->getTodosProtocolosPaginado($this->_getOffset(),
                                                                    $this->_getPageSize(),
                                                                    $this->_getSearchTerm());
        
        return $protocolos;
    }
    
    protected function _getListaProtocolosDestinoNovo($protocoloOrigemId, $tipoProcessoId = null, $filter = null, $offset = 0, $pageSize = 20)
    {
        $protocoloService = new ProtocoloService($this->getTicket());
        $protocolos = $protocoloService->getProtocolosDestino($protocoloOrigemId,
                                                              $tipoProcessoId,
                                                              $this->_getSearchTerm(),
                                                              $offset,
                                                              $this->_getPageSize());
        
        return $protocolos;
    }
    
    
    protected function _getSearchTerm()
    {
        return $this->getRequest()->getParam('term', null);
    }

    protected function _getProtocolosDestino()
    {
        $protocoloService = new ProtocoloService($this->getTicket());
        $protocolos = $protocolo->getTodosProtocolosPaginado($this->_getOffset(),
                                                             $this->_getPageSize(),
                                                             $this->_getSearch());
        
        return $this->_convertProtocolosDestinoToDataTablesRow($protocolos);
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
        $this->view->output = $this->_getOutputProtocolos($this->_getProtocolosAutocomplete());
    }
    
    protected function _getProtocolosAutocomplete()
    {
        $protocoloService = new ProtocoloService($this->getTicket());
        $protocolos = $protocoloService->getTodosProtocolosPaginado($this->_getOffset(),
                                                                    $this->_getPageSize(),
                                                                    $this->_getSearchTerm());
        
        return $protocolos;
    }
}
