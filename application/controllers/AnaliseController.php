<?php

require_once('BaseTramitacaoController.php');

class AnaliseController extends BaseTramitacaoController
{

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                $processosSelecionados = $this->getRequest()->getParam('processos');

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
        $this->view->paginator = $this->_helper->paginator()->paginate(
            $service->getCaixaAnalise(
                $this->_helper->paginator()->getOffset(), $this->_helper->paginator()->getPageSize(), $this->view->q, $this->view->assuntoId
            )
        );

        $session = new Zend_Session_Namespace('ap');
        if ($session->updateaposentadoria) {
            $this->view->updateaposentadoria = $session->updateaposentadoria;
            Zend_Session::namespaceUnset('ap');
        } elseif ($session->updatemassa) {
            $this->view->updatemassa = $session->updatemassa;
            Zend_Session::namespaceUnset('ap');
        }
    }

    public function atualizarAposentadoriaAction()
    {
        $this->ajaxNoRender();
        
        if ($this->isPostAjax()) {
            $res = $this->_atualizarAposentadoria($this->_getParam('ids'), array('status' => 'EXTERNO'));
            die($res ? 'atualizado' : 'erro');
        }
    }
    
    public function atualizarAposentadoriaMassaAction()
    {
        $this->ajaxNoRender();
        
        if ($this->isPostAjax()) {
            $ids = $this->_getParam('ids');
            $destino = array_pop($ids);
            
            $res = $this->_atualizarAposentadoria($ids, array('LOTACAO_ATUAL' => $destino));
            die($res ? 'atualizado' : 'erro');
        }
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

    public function comprovanteRecebimentoAction()
    {
        $this->_helper->layout()->setLayout('relatorio');
        $session = new Zend_Session_Namespace('comprovanteRecebimento');
        $processosSelecionados = $session->processos;
        $processos = $this->_getListaCarregadaProcessos($processosSelecionados);
        $this->view->processos = $processos;
    }

}
