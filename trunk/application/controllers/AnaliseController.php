<?php

require_once('BaseTramitacaoController.php');

class AnaliseController extends BaseTramitacaoController
{

    public function indexAction()
    {
        if ($this->_getParam('mostrar') == 'true') {
            if ($this->_getParam('lotacao_id')) {
                $lotacaoUsuario = new Zend_Session_Namespace('lotacao_usuario');
                $lotacaoUsuario->id = $this->_getParam('lotacao_id');
            }
            $this->view->mostrar = $this->_getParam('mostrar');
        }
        
        if ($this->getRequest()->isPost()) {
            try {
                $processosSelecionados = $this->getRequest()->getParam('processos');

                if (count($processosSelecionados) > self::LIMITE_MOVIMENTACAO) {
                    $this->setErrorMessage("Atenção, você não pode movimentar mais do que " . self::LIMITE_MOVIMENTACAO . " processos por vez!");
                    $this->_redirectAnalise();
                }

                if (!$processosSelecionados) {
                    throw new Exception('Por favor, selecione pelo menos um processo.');
                }

                if ($this->_isPostComprovanteRecebimento()) {
                    $session = new Zend_Session_Namespace('comprovanteRecebimento');
                    $session->processos = $processosSelecionados;
                    $this->_redirectComprovanteRecebimento();
                } elseif ($this->_isPostEncaminhamento()) {
                    if (count($processosSelecionados) == 1) {
                        $this->_redirectEncaminharProcesso($processosSelecionados[0]);
                    } else {
                        $session = new Zend_Session_Namespace('encaminhar');
                        $session->processos = $processosSelecionados;
                        $this->_redirectEncaminhar();
                    }
                } elseif ($this->_isPostArquivamento()) {
                    $session = new Zend_Session_Namespace('arquivar');
                    $session->processos = $processosSelecionados;
                    $this->_redirectArquivar();
                } elseif ($this->_isPostEncaminhamentoExterno()) {
                    $session = new Zend_Session_Namespace('encaminharExternos');
                    $session->processos = $processosSelecionados;
                    $this->_redirectEncaminharExternos();
                } elseif ($this->_isPostCriarDespacho()) {
                    /*
                     * Apaga a lista de arquivos na sessão utilizada pelo controlador 'Despachar'
                     */
                    $sessionDespachar = new Zend_Session_Namespace('despachar');
                    unset($sessionDespachar->filesToUpload);

                    $session = new Zend_Session_Namespace('comentar');
                    $session->processos = $processosSelecionados;
                    $this->_redirectComentar();
                } elseif ($this->_isPostProcessoAntigo()) {
                    if (count($processosSelecionados) != 1) {
                        $this->setErrorMessage('Você só pode manipular um processo por vez para essa opção');

                        $this->_redirectEmAnalise();
                    }

                    $this->_redirectProcessoAntigo($processosSelecionados[0]);
                }
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }

        $this->view->q = urldecode($this->_getParam('q'));
        $this->view->tipoProcessoId = urldecode($this->_getParam('tipo-processo'));
        $this->view->assuntoId = urldecode($this->_getParam('assunto'));
        $this->view->tiposProcesso = $this->_getListaTiposProcesso();

        if ($this->view->q ||  $this->view->tipoProcessoId) {
            $service = new Spu_Service_Tramitacao($this->getTicket());
            $busca = $service->getCaixaAnalise(
                $this->_helper->paginator()->getOffset(), 
                $this->_helper->paginator()->getPageSize(),
                $this->view->q, 
                $this->view->assuntoId
            );
            $this->view->totalDocumentos = count($busca);
            $this->view->paginator = $this->_helper->paginator()->paginate($busca);
            $this->view->mostrar = 'true';
        }

        $session = new Zend_Session_Namespace('ap');
        if ($session->updateaposentadoria) {
            $this->view->updateaposentadoria = $session->updateaposentadoria;
        }
        Zend_Session::namespaceUnset('ap');
        $this->view->listaOrigens = $this->_getListaOrigens();
    }
    
    public function encaminharOkAction()
    {
        $this->setSuccessMessage('Processos encaminhados com sucesso.');
        $this->_redirectAnalise();
    }
    
    public function encaminharFalhaAction()
    {
        $processo_falha = $this->getRequest()->getParam('np');
        $processo_falha = str_replace("_", "/", $processo_falha);
        $this->setErrorMessage("Falha ao encaminhar o processo de número $processo_falha");
        $this->_redirectAnalise();
    }

    protected function _redirectEncaminharProcesso($processoId)
    {
        $this->_helper->redirector('encaminhar', 'processo', null, array('id' => $processoId));
    }

    protected function _isPostComprovanteRecebimento()
    {
        return ($this->getRequest()->getParam('comprovanteRecebimento', false) !== false) ? true : false;
    }

    protected function _isPostEncaminhamento()
    {
        return ($this->getRequest()->getParam('encaminhar', false) !== false) ? true : false;
    }

    protected function _isPostArquivamento()
    {
        return ($this->getRequest()->getParam('arquivar', false) !== false) ? true : false;
    }

    protected function _isPostProcessoAntigo()
    {
        return ($this->getRequest()->getParam('processo-antigo', false) !== false) ? true : false;
    }

    protected function _isPostEncaminhamentoExterno()
    {
        return ($this->getRequest()->getParam('externo', false) !== false) ? true : false;
    }

    protected function _isPostCriarDespacho()
    {
        return ($this->getRequest()->getParam('comentar', false) !== false) ? true : false;
    }

    protected function _redirectComprovanteRecebimento()
    {
        $this->_helper->redirector('comprovante-recebimento');
    }

    protected function _redirectArquivar()
    {
        $this->_helper->redirector('arquivar', 'arquivo', 'default');
    }

    protected function _redirectEncaminharExternos()
    {
        $this->_helper->redirector('externo', 'encaminhar', 'default');
    }

    protected function _redirectComentar()
    {
        $this->_helper->redirector('index', 'despachar', 'default');
    }
    
    protected function _redirectAnalise()
    {
        $this->_helper->redirector('index', 'analise', 'default');
    }

    protected function _redirectProcessoAntigo($id)
    {
        $this->_helper->redirector('antigo', 'processo');
    }

    public function comprovanteRecebimentoAction()
    {
        $this->_helper->layout()->setLayout('relatorio');
        $session = new Zend_Session_Namespace('comprovanteRecebimento');
        $processosSelecionados = $session->processos;
        $processos = $this->_getListaCarregadaProcessos($processosSelecionados);
        $this->view->processos = $processos;
    }

}
