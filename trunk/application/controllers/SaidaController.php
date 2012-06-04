<?php

require_once('BaseTramitacaoController.php');

class SaidaController extends BaseTramitacaoController
{

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                $processosSelecionados = $this->getRequest()->getParam('processos');
                if (count($processosSelecionados) > self::LIMITE_MOVIMENTACAO) {
                    $this->setErrorMessage("Atenção, você não pode movimentar mais do que " . self::LIMITE_MOVIMENTACAO . " processos por vez!");
                    $this->_redirectSaida();
                }
                
                if ($this->_isPostComprovanteEncaminhamento()) {
                    $session = new Zend_Session_Namespace('comprovanteEncaminhamento');
                    $session->processos = $this->getRequest()->getParam('processos');
                    $this->_redirectComprovanteEncaminhamento();
                } else {
                    $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
                    $tramitacaoService->cancelarEnvios($this->getRequest()->getPost());
                    $this->setSuccessMessage('Envios cancelados com sucesso.');
                    $this->_redirectEntrada();
                }
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }

        $this->view->q = urldecode($this->_getParam('q'));
        $this->view->tipoProcessoId = urldecode($this->_getParam('tipo-processo'));
        $this->view->assuntoId = urldecode($this->_getParam('assunto'));
        $this->view->tiposProcesso = $this->_getListaTiposProcesso();

        $service = new Spu_Service_Tramitacao($this->getTicket());
        $busca = $service->getCaixaSaida(
            $this->_helper->paginator()->getOffset(), 
            $this->_helper->paginator()->getPageSize(), 
            $this->view->q, 
            $this->view->assuntoId
        );
        
        $this->view->paginator = $this->_helper->paginator()->paginate($busca);
        $this->view->totalDocumentos = count($busca);
    }

    protected function _isPostComprovanteEncaminhamento()
    {
        return ($this->getRequest()->getParam('comprovanteEncaminhamento', false) !== false) ? true : false;
    }

    protected function _redirectComprovanteEncaminhamento()
    {
        $this->_helper->redirector('comprovante-encaminhamento');
    }

    public function comprovanteEncaminhamentoAction()
    {
        $this->_helper->layout()->setLayout('relatorio');
        $session = new Zend_Session_Namespace('comprovanteEncaminhamento');
        $processosSelecionados = $session->processos;
        $processos = $this->_getListaCarregadaProcessos($processosSelecionados);
        $this->view->processos = $processos;
    }
    
    protected function _redirectSaida()
    {
        $this->_helper->redirector('index', 'saida');
    }

}