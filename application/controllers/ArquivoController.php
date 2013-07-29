<?php

require_once('BaseTramitacaoController.php');

class ArquivoController extends BaseTramitacaoController {

    public function indexAction() {
        if ($this->_getParam('mostrar') == 'true') {

            $this->view->mostrar = $this->_getParam('mostrar');

            $this->view->q = urldecode($this->_getParam('q'));
            $this->view->tipoProcessoId = urldecode($this->_getParam('tipo-processo'));
            $this->view->assuntoId = urldecode($this->_getParam('assunto'));
            $this->view->tiposProcesso = $this->_getListaTiposProcesso();
            $service = new Spu_Service_Tramitacao($this->getTicket());
            $busca = $service->getCaixaArquivo(
                            $this->_helper->paginator()->getOffset(), $this->_helper->paginator()->getPageSize(), $this->view->q, $this->view->assuntoId
            );
            $this->view->paginator = $this->_helper->paginator()->paginate($busca);
            $this->view->totalDocumentos = count($busca);
        }

        if ($this->getRequest()->isPost()) {
            try {
                $processosSelecionados = $this->getRequest()->getParam('processos');

                if (count($processosSelecionados) > self::LIMITE_MOVIMENTACAO) {
                    $this->setErrorMessage("Atenção, você não pode movimentar mais do que " . self::LIMITE_MOVIMENTACAO . " processos por vez!");
                    $this->_redirectArquivo();
                }

                $session = new Zend_Session_Namespace('reabrir');
                $session->processos = $processosSelecionados;
                $this->_redirectReabrir();
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }

        if ($this->_getParam('q')) {
            $this->view->q = urldecode($this->_getParam('q'));
            $this->view->tipoProcessoId = urldecode($this->_getParam('tipo-processo'));
            $this->view->assuntoId = urldecode($this->_getParam('assunto'));
            $this->view->tiposProcesso = $this->_getListaTiposProcesso();

            $service = new Spu_Service_Tramitacao($this->getTicket());
            $busca = $service->getCaixaArquivo(
                            $this->_helper->paginator()->getOffset(), $this->_helper->paginator()->getPageSize(), $this->view->q, $this->view->assuntoId
            );

            $this->view->paginator = $this->_helper->paginator()->paginate($busca);
            $this->view->totalDocumentos = count($busca);
            $this->view->mostrar = 'true';
        }
        $this->view->tiposProcesso = $this->_getListaTiposProcesso();

        $session = new Zend_Session_Namespace('ap');
        if (isset($session->updateaposentadoria)) {
            $this->view->updateaposentadoria = $session->updateaposentadoria;
        }
        Zend_Session::namespaceUnset('ap');
    }

    protected function _redirectArquivo() {
        $this->_helper->redirector('index', 'arquivo');
    }

    protected function _redirectArquivar() {
        $this->_helper->redirector('arquivar', 'arquivo');
    }

    protected function _redirectReabrir() {
        $this->_helper->redirector('reabrir', $this->getController(), 'default');
    }

    public function arquivarAction() {
        if ($this->getRequest()->isPost()) {

            $this->_limparCache();

            try {
                $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
                $tramitacaoService->arquivarVarios($this->getRequest()->getPost());
                $this->setSuccessMessage('Processos arquivados com sucesso.');

                $session = new Zend_Session_Namespace('ap');
                $session->updateaposentadoria['ids'] = $this->_getParam('processos');
                $session->updateaposentadoria['colunas'] = array('status' => 'ARQUIVANDO');

                $this->_redirectArquivo();
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }

        $processos = array();

        try {
            $session = new Zend_Session_Namespace('arquivar');
            $processosSelecionados = $session->processos;
            $processos = $this->_getListaCarregadaProcessos($processosSelecionados);
            $listaStatusArquivamento = $this->_getListaStatusArquivamento();
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectEmAnalise();
        }

        $this->view->processos = $processos;
        $this->view->listaStatusArquivamento = $listaStatusArquivamento;
    }

    public function arquivarOkAction() {
        $this->setSuccessMessage('Processos arquivados com sucesso.');
        $this->_redirectArquivo();
    }

    public function arquivarFalhaAction() {
        $processo_falha = $this->getRequest()->getParam('np');
        $processo_falha = str_replace("_", "/", $processo_falha);
        $this->setErrorMessage("Falha ao arquivar o processo de número $processo_falha");

        $this->_redirectEmAnalise();
    }

    protected function _getListaStatusArquivamento() {
        $statusArquivamentoService = new Spu_Service_StatusArquivamento($this->getTicket());
        $opcoes = $statusArquivamentoService->fetchAll();
        $listaStatusArquivamento = array();
        foreach ($opcoes as $opcao) {
            $listaStatusArquivamento[$opcao->id] = $opcao->descricao;
        }

        if (count($listaStatusArquivamento) == 0) {
            throw new Exception(
                    'Não existe nenhum status de arquivamento cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }

        return $listaStatusArquivamento;
    }

    public function reabrirAction() {
        if ($this->getRequest()->isPost()) {
            try {
                $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
                $idParent = $tramitacaoService->getIdCaixa("caixaanalise");
                $postData = $this->getRequest()->getPost();
                $postData["caixaAnaliseId"] = substr($idParent, 24);
                $tramitacaoService->reabrirVarios($postData);
                $this->setSuccessMessage('Processos reabertos com sucesso.');

                $session = new Zend_Session_Namespace('ap');
                $session->updateaposentadoria['ids'] = $this->_getParam('processos');
                $session->updateaposentadoria['colunas'] = array('status' => 'TRAMITANDO');

                $this->_redirectEmAnalise();
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }

        $processos = array();

        try {
            $session = new Zend_Session_Namespace('reabrir');
            $processosSelecionados = $session->processos;
            $processos = $this->_getListaCarregadaProcessos($processosSelecionados);
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectArquivo();
        }

        $this->view->processos = $processos;
    }

    private function _limparCache() {
        $this->setMessageCache();

        $this->_redirectArquivar();
    }

}
