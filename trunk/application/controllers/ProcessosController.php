<?php
Loader::loadEntity('Processo');
class ProcessosController extends BaseController
{
    public function indexAction()
    {
        $processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaEntrada();
    }
    
    public function detalhesAction()
    {
    	try {
    		$idProcesso = $this->_getIdProcessoUrl();
	    	$processo = new Processo($this->getTicket());
	        if ($idProcesso) {
	            $processo->carregarPeloId($idProcesso);
	        }
    	} catch (Exception $e) {
    		
    	}
    	$this->view->processo = $processo;
    }
    
	protected function _getIdProcessoUrl()
    {
        $idProcesso = $this->getRequest()->getParam('id');
        return $idProcesso;
    }
}