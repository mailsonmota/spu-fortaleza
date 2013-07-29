<?php

require_once('BaseTramitacaoController.php');

class ExternosController extends BaseTramitacaoController
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


        if ($this->view->q || $this->view->tipoProcessoId) {
            $service = new Spu_Service_Tramitacao($this->getTicket());
            $busca = $service->getCaixaExternos(
                    $this->_helper->paginator()->getOffset(), $this->_helper->paginator()->getPageSize(), $this->view->q, $this->view->assuntoId
            );

            $this->view->paginator = $this->_helper->paginator()->paginate($busca);
            $this->view->totalDocumentos = count($busca);
            $this->view->mostrar = 'true';
        }
        
        $this->view->listaOrigens = $this->_getListaOrigens();
    }

    protected function _redirectExternos()
    {
        $this->_helper->redirector('index', 'externos');
    }

}
