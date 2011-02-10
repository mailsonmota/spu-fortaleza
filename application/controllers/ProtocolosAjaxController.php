<?php
require_once 'BaseDataTablesController.php';
Loader::loadDao('ProtocoloDao');
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
    	$protocoloDao = new ProtocoloDao($this->getTicket());
    	$protocolos = $protocoloDao->getTodosProtocolosPaginado($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
    	
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
    	$protocolo = new Protocolo($this->getTicket());
        $protocolos = $protocolo->listarTodosPaginado($this->_getOffset(), $this->_getPageSize(), $this->_getSearchTerm());
        
        return $protocolos;
    }
    
    protected function _getSearchTerm()
    {
    	return $this->getRequest()->getParam('term', null);
    }

    protected function _getProtocolosDestino()
    {
        $protocolo = new Protocolo($this->getTicket());
        $protocolos = $protocolo->listarTodosPaginado($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
        
        return $this->_convertProtocolosDestinoToDataTablesRow($protocolos);
    }
    
    protected function _convertProtocolosDestinoToDataTablesRow($protocolos)
    {
    	$rows = array();
        foreach ($protocolos as $protocolo) {
        	$row = array();
            $row['input'] = "<input type='radio' name='protocoloDestino' value='" . $protocolo->id . "' />";
        	$row['nome'] = $protocolo->path;
            
            $rows[] = $row;
        }
        
        return $rows;
    }

    /*public function listarDestinosAction()
    {
    	$this->_rows = $this->_getProtocolosDestino();
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->output = $this->_getOutput();
    }
    
    protected function _getProtocolosDestino()
    {
        $protocolo = new Protocolo($this->getTicket());
        $protocolos = $protocolo->listarTodosPaginado($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
        
        return $this->_convertProtocolosDestinoToDataTablesRow($protocolos);
    }
    
    protected function _convertProtocolosDestinoToDataTablesRow($protocolos)
    {
    	$rows = array();
        foreach ($protocolos as $protocolo) {
        	$row = array();
            $row['input'] = "<input type='radio' name='protocoloDestino' value='" . $protocolo->id . "' />";
        	$row['nome'] = $protocolo->path;
            
            $rows[] = $row;
        }
        
        return $rows;
    }*/
}
