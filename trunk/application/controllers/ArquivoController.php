<?php

require_once('BaseTramitacaoController.php');

class ArquivoController extends BaseTramitacaoController
{

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                $processosSelecionados = $this->getRequest()->getParam('processos');
                $session = new Zend_Session_Namespace('reabrir');
                $session->processos = $processosSelecionados;
                $this->_redirectReabrir();
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }

        $this->view->q = urldecode($this->_getParam('q'));
        $this->view->tipoProcessoId = urldecode($this->_getParam('tipo-processo'));
        $this->view->assuntoId = urldecode($this->_getParam('assunto'));
        $this->view->tiposProcesso = $this->_getListaTiposProcesso();

        $service = new Spu_Service_Tramitacao($this->getTicket());
        $this->view->paginator = $this->_helper->paginator()->paginate(
            $service->getCaixaArquivo(
                $this->_helper->paginator()->getOffset(), $this->_helper->paginator()->getPageSize(), $this->view->q, $this->view->assuntoId
            )
        );
        $session = new Zend_Session_Namespace('ap');
        if (isset($session->updateaposentadoria)) {
            $this->view->updateaposentadoria = $session->updateaposentadoria;
        }
        Zend_Session::namespaceUnset('ap');
    }

    protected function _redirectReabrir()
    {
        $this->_helper->redirector('reabrir', $this->getController(), 'default');
    }

    public function arquivarAction()
    {
        if ($this->getRequest()->isPost()) {
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

    public function atualizarAposentadoriaAction()
    {
        $this->ajaxNoRender();
        
        if ($this->isPostAjax()) {
            $res = $this->_atualizarAposentadoria($this->_getParam('ids'), $this->_getParam('colunas'));
            die($res ? 'atualizado' : 'erro');
        }
    }

    protected function _getListaStatusArquivamento()
    {
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

    public function reabrirAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
                $tramitacaoService->reabrirVarios($this->getRequest()->getPost());
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

}