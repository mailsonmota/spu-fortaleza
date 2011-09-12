<?php
require_once 'BaseDataTablesController.php';
class TramitacaoAjaxController extends BaseDataTablesController
{
    public function entradaAction()
    {
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->processos = $this->_getCaixaEntrada();
    }
    
    protected function _getCaixaEntrada()
    {
        try {
            $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
            return $tramitacaoService->getCaixaEntrada($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
        } catch (Exception $e) {
            return $this->_getJsonErrorRow($e);
        }
    }
    
    public function analiseAction()
    {
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->processos = $this->_getCaixaAnalise();
    }
    
    protected function _getCaixaAnalise()
    {
        try {
            $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
            return $tramitacaoService->getCaixaAnalise($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
        } catch (Exception $e) {
            return $this->_getJsonErrorRow($e);
        }
    }
    
    public function externosAction()
    {
    	$this->_total = 1000;
    
    	$this->_helper->layout()->disableLayout();
    	$this->view->processos = $this->_getCaixaExternos();
    }
    
    protected function _getCaixaExternos()
    {
    	try {
    		$tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
    		return $tramitacaoService->getCaixaExternos($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
    	} catch (Exception $e) {
    		return $this->_getJsonErrorRow($e);
    	}
    }
    
    public function copiasAction()
    {
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->copias = $this->_getCopias();
    }
    
    protected function _getCopias()
    {
        try {
            $copiaService = new Spu_Service_CopiaProcesso($this->getTicket());
            return $copiaService->getCopias($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
        } catch (Exception $e) {
            return $this->_getJsonErrorRow($e);
        }
    }
    
    public function arquivoAction()
    {
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->processos = $this->_getCaixaArquivo();
    }
    
    protected function _getCaixaArquivo()
    {
        try {
            $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
            return $tramitacaoService->getCaixaArquivo($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
        } catch (Exception $e) {
            return $this->_getJsonErrorRow($e);
        }
    }
    
    public function saidaAction()
    {
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->processos = $this->_getCaixaSaida();
    }
    
    protected function _getCaixaSaida()
    {
        try {
            $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
            return $tramitacaoService->getCaixaSaida($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
        } catch (Exception $e) {
            return $this->_getJsonErrorRow($e);
        }
    }
    
    public function enviadosAction()
    {
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->processos = $this->_getCaixaEnviados();
    }
    
    protected function _getCaixaEnviados()
    {
        try {
            $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
            return $tramitacaoService->getCaixaEnviados($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
        } catch (Exception $e) {
            return $this->_getJsonErrorRow($e);
        }
    }
}