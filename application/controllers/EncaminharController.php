<?php
require_once('BaseTramitacaoController.php');
class EncaminharController extends BaseTramitacaoController
{
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
                $tramitacaoService->tramitarVarios($this->getRequest()->getPost());
                $this->setSuccessMessage('Processos tramitados com sucesso.');
                $this->_redirectEmAnalise();
            } catch (Exception $e) {
            	$errorMessage = $e->getMessage();
            	if (strpos($errorMessage, 'Este processo ja existe no destino') > -1) {
            		$errorMessage = 'Um dos processos selecionados já existe no destino.';
            	}
            	$this->setMessageForTheView($errorMessage, 'error');
            }
        }
        
        $processos = array();
        
        try {
            $session = new Zend_Session_Namespace('encaminhar');
            $processosSelecionados = $session->processos;
            $processos = $this->_getListaCarregadaProcessos($processosSelecionados);
            
            $origemId = null;
            foreach ($processos as $processo) {
                if ($origemId && $processo->protocolo->id != $origemId) {
                    throw new Exception('Por favor, selecione processos que estejam no mesmo protocolo.');
                }
                $origemId = $processo->protocolo->id;
            }
            
            $service = new Spu_Service_Protocolo($this->getTicket());
            $listaProtocolos = $service->getProtocolosRaiz();
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectEmAnalise();
        }
        
        $this->view->processos = $processos;
        $this->view->listaProtocolos = $listaProtocolos;
        $this->view->origemId = $origemId;
    }
    
    protected function _getListaCarregadaProcessos($listaComIdsProcessos)
    {
        $processos = array();
        foreach ($listaComIdsProcessos as $processoId) {
            $processoService = new Spu_Service_Processo($this->getTicket());
            $processo = $processoService->getProcesso($processoId);
            $processos[] = $processo;
        }
        
        return $processos;
    }
    
    public function externoAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
                $tramitacaoService->tramitarExternos($this->getRequest()->getPost());
                $this->setSuccessMessage('Processos tramitados com sucesso.');
                $this->_redirectEmAnalise();
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }
        
        $processos = array();
        
        try {
            $session = new Zend_Session_Namespace('encaminharExternos');
            $processosSelecionados = $session->processos;
            $processos = $this->_getListaCarregadaProcessos($processosSelecionados);
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectEmAnalise();
        }
        
        $this->view->processos = $processos;
    }
}