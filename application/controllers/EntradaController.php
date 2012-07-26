<?php

require_once('BaseTramitacaoController.php');

class EntradaController extends BaseTramitacaoController
{

    public function indexAction()
    {
        $this->view->q = urldecode($this->_getParam('q'));
        $this->view->tipoProcessoId = urldecode($this->_getParam('tipo-processo'));
        $this->view->assuntoId = urldecode($this->_getParam('assunto'));
        $this->view->tiposProcesso = $this->_getListaTiposProcesso();
        
        if ($this->view->q) {
            $service = new Spu_Service_Tramitacao($this->getTicket());
            $busca = $service->getCaixaEntrada(
                $this->_helper->paginator()->getOffset(), 
                $this->_helper->paginator()->getPageSize(), 
                $this->view->q, 
                $this->view->assuntoId
            );
            $this->view->totalDocumentos = count($busca);
            $this->view->paginator = $this->_helper->paginator()->paginate($busca);
        }
    }

    public function receberAction()
    {
        $processosSelecionados = $this->getRequest()->getParam('processos');
        
        try {
            if (count($processosSelecionados) > self::LIMITE_MOVIMENTACAO) {
                $this->setErrorMessage("Atenção, você não pode movimentar mais do que " . self::LIMITE_MOVIMENTACAO . " processos por vez!");
                $this->_redirectEntrada();
            }
            
            if (!$this->getRequest()->isPost() OR !$this->getRequest()->getParam('processos')) {
                throw new Exception("Por favor, selecione pelo menos um processo para receber.");
            }
            
            $this->_limparCache();
            
            $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
            $tramitacaoService->receberVarios($this->getRequest()->getPost());
            $this->setSuccessMessage('Processos recebidos com sucesso.');
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectEntrada();
        }
        $this->_redirectEmAnalise();
    }
    
    public function receberOkAction()
    {
        $this->setSuccessMessage('Processos recebidos com sucesso.');
        $this->_redirectEntrada();
    }
    
    private function _limparCache()
    {
        $this->setMessageCache();
        
        $this->_redirectEntrada();
    }


    public function receberFalhaAction()
    {
        $processo_falha = $this->getRequest()->getParam('np');
        $processo_falha = str_replace("_", "/", $processo_falha);
        $this->setErrorMessage("Falha ao receber o processo de número $processo_falha");
        $this->_redirectEntrada();
    }

}