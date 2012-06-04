<?php

require_once('BaseTramitacaoController.php');

class ExternosController extends BaseTramitacaoController
{

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                $processosSelecionados = $this->getRequest()->getParam('processos');
                if (count($processosSelecionados) > self::LIMITE_MOVIMENTACAO) {
                    $this->setErrorMessage("Atenção, você não pode movimentar mais do que " . self::LIMITE_MOVIMENTACAO . " processos por vez!");
                    $this->_redirectExternos();
                }
                $session = new Zend_Session_Namespace('encaminhar');
                $session->processos = $processosSelecionados;
                $this->_redirectEncaminhar();
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }

        $this->view->q = urldecode($this->_getParam('q'));
        $this->view->tipoProcessoId = urldecode($this->_getParam('tipo-processo'));
        $this->view->assuntoId = urldecode($this->_getParam('assunto'));
        $this->view->tiposProcesso = $this->_getListaTiposProcesso();

        $service = new Spu_Service_Tramitacao($this->getTicket());
        $busca = $service->getCaixaExternos(
            $this->_helper->paginator()->getOffset(), 
            $this->_helper->paginator()->getPageSize(), 
            $this->view->q, 
            $this->view->assuntoId
        );
        
        $this->view->paginator = $this->_helper->paginator()->paginate($busca);
        $this->view->totalDocumentos = count($busca);
    }
    
    protected function _redirectExternos()
    {
        $this->_helper->redirector('index', 'externos');
    }

}