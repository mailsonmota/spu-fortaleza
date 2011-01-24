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
    	
    	return $this->_convertProtocolosToDataTablesRow($protocolos);
    }
    
    protected function _convertProtocolosToDataTablesRow($protocolos)
    {
    	$rows = array();
    	foreach ($protocolos as $protocolo) {
    		$row = array();
    		$row['nome'] = $protocolo->nome;
    		$row['detalhes'] = "<a href='" . $this->_helper->url('editar', 'protocolos', null, array('id' => $protocolo->id)) . "'>Detalhes</a>";
    		
    		$rows[] = $row;
    	}
    	
    	return $rows;
    }
}