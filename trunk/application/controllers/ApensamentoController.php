<?php

require_once('BaseTramitacaoController.php');

class ApensamentoController extends BaseTramitacaoController
{

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();

            if (array_key_exists('apensamento', $postData)) {
                $this->_checarEscolhaDeProcesso($postData, $this->getAction());

                $session = new Zend_Session_Namespace('apensamentoSession');
                $session->processoPrincipalId = $postData['processos'][0];

                $this->_redirectEscolherApensados();
            }
        }

        $this->view->q = $this->_getParam('q');
        $this->view->assuntoId = urldecode($this->_getParam('assunto'));
        $this->view->tipoProcessoId = urldecode($this->_getParam('tipoprocesso'));
        $this->view->tiposProcesso = $this->_getListaTiposProcesso();

        if ($this->view->q) {
            $service = new Spu_Service_Tramitacao($this->getTicket());
            $busca = $service->getCaixaAnalise(
                $this->_helper->paginator()->getOffset(), $this->_helper->paginator()->getPageSize(), $this->view->q, $this->view->assuntoId
            );
            $this->view->totalDocumentos = count($busca);
            $this->view->paginator = $this->_helper->paginator()->paginate($busca);
        }
    }

    public function escolherprocessoAction()
    {
        $session = new Zend_Session_Namespace('apensamentoSession');

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();

            if (array_key_exists('escolherprocesso', $postData)) {
                $this->_checarEscolhaDeProcesso($postData, $this->getAction(), false);

                $session->processosApensados = $postData['processos'];

                $this->_redirectConfirmacao();
            }
        }

        $this->view->q = urldecode($this->_getParam('q'));
        $this->view->assuntoId = urldecode($this->_getParam('assunto'));
        $this->view->tipoProcessoId = urldecode($this->_getParam('tipoprocesso'));
        $this->view->tiposProcesso = $this->_getListaTiposProcesso();
        $this->view->processoPrincipalId = $session->processoPrincipalId;

        $processoService = new Spu_Service_Processo($this->getTicket());
        $processo = $processoService->getProcesso($session->processoPrincipalId);
        $this->view->processo = $processo;

        if ($this->view->q) {
            $caixaAnaliseApensamento = $processoService->getCaixaAnaliseIncorporacao(
                $processo, $this->_helper->paginator()->getOffset(), $this->_helper->paginator()->getPageSize(), $this->view->q
            );

            for ($i = 0; $i < count($caixaAnaliseApensamento); $i++) {
                if ($caixaAnaliseApensamento[$i]->id == $processo->id) {
                    unset($caixaAnaliseApensamento[$i]);
                    array_values($caixaAnaliseApensamento);
                }
            }

            $this->view->paginator = $this->_helper->paginator()->paginate($caixaAnaliseApensamento);
        }
    }

    public function confirmacaoAction()
    {
        $session = new Zend_Session_Namespace('apensamentoSession');
        $processoService = new Spu_Service_Processo($this->getTicket());
        if ($this->getRequest()->isPost()) {
            $data = array();
            $data['principal'] = $session->processoPrincipalId;
            $data['apensados'] = $session->processosApensados;
            
            try {
                $processoService->apensar($data);
            } catch (AlfrescoApiException $e) {
                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
            $this->_redirectConclusao();
        }

        $this->view->principal = $processoService->getProcesso($session->processoPrincipalId);

        foreach ($session->processosApensados as $processoIncorporado) {
            $apensados[] = $processoService->getProcesso($processoIncorporado);
        }

        $this->view->apensados = $apensados;
    }

    public function conclusaoAction()
    {
        $session = new Zend_Session_Namespace('apensamentoSession');
        $processoService = new Spu_Service_Processo($this->getTicket());

        $this->view->principal = $processoService->getProcesso($session->processoPrincipalId);

        foreach ($session->processosApensados as $processoIncorporado) {
            $incorporados[] = $processoService->getProcesso($processoIncorporado);
        }

        $this->view->apensados = $incorporados;
    }

    public function pesquisarAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->_helper->redirector(null, null, null, array('q' => urlencode($_POST['q'])));
        }
    }

    public function desapensarAction()
    {
        $this->ajaxNoRender();
        try {
            $tramitacao = new Spu_Service_Tramitacao($this->getTicket());
            $data = array();
            $data['principal'] = substr($tramitacao->getIdCaixa("caixaanalise"), 24);
            $data['desapensados'] = array($this->getRequest()->getPost("processoId"));

            $processoService = new Spu_Service_Processo($this->getTicket());
            $processoService->desapensar($data);
        } catch (Exception $exc) {
            header("HTTP/1.1 500 Internal Server Error");
            echo $exc->getMessage();
        }
    }

    protected function _checarEscolhaDeProcesso($postData, $action, $limite = true)
    {
        if ($limite && count($postData["processos"]) > 1) {
            $this->setErrorMessage('Atenção, você só pode escolher 1  processo como principal');
            $this->_redirectEscolherPrincipal();
        }

        if (empty($postData['processos'][0])) {
            $this->setErrorMessage('Nenhum processo foi escolhido.');
            $this->_helper->redirector($action);
        }
    }

    protected function _redirectEscolherPrincipal()
    {
        $this->_helper->redirector('index', $this->getController(), 'default');
    }

    protected function _redirectEscolherApensados()
    {
        $this->_helper->redirector('escolherprocesso', $this->getController(), 'default');
    }

    protected function _redirectConfirmacao()
    {
        $this->_helper->redirector('confirmacao', $this->getController(), 'default');
    }

    protected function _redirectConclusao()
    {
        $this->_helper->redirector('conclusao', $this->getController(), 'default');
    }

}