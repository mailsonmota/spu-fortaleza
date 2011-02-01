<?php
require_once 'BaseDataTablesController.php';
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
    	$protocolo = new Protocolo($this->getTicket());
    	$protocolos = $protocolo->listarTodosPaginado($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
    	
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
    }
}