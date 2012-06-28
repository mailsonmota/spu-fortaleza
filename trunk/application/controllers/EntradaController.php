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

        $service = new Spu_Service_Tramitacao($this->getTicket());
        
//        $service->getCaixaEntradaCmis(
//            '52dbf370-5453-4fce-b752-fc756660a56a', 
//            $this->_helper->paginatorCmis()->getOffSet(), 
//            $this->_helper->paginatorCmis()->getMaxItems()
//        );
        
        $busca = $service->getCaixaEntrada(
            $this->_helper->paginator()->getOffset(), 
            $this->_helper->paginator()->getPageSize(), 
            $this->view->q, 
            $this->view->assuntoId
        );
        
        $this->view->totalDocumentos = count($busca);
        $this->view->paginator = $this->_helper->paginator()->paginate($busca);
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

            $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
            $tramitacaoService->receberVarios($this->getRequest()->getPost());
            $this->setSuccessMessage('Processos recebidos com sucesso.');
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectEntrada();
        }
        $this->_redirectEmAnalise();
    }

}