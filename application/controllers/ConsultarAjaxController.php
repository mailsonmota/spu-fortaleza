<?php
require_once 'BaseDataTablesController.php';
class ConsultarAjaxController extends BaseDataTablesController
{
    public function anexoResultadosAction()
    {
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->anexos = $this->_getAnexoResultados();
    }
    
    protected function _getAnexoResultados()
    {
        try {
        	$processoService = new Spu_Service_Processo($this->getTicket());
        	return $processoService->consultarAnexos($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
        } catch (Exception $e) {
            return $this->_getJsonErrorRow($e);
        }
    }
}