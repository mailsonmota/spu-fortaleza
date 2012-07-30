<?php

class ProcessosAjaxController extends BaseController
{

    public function init()
    {
        $this->_helper->layout->disableLayout();

        if (!$this->isPostAjax())
            die();
    }

    public function caixaEntradaAction()
    {
        $this->getRequest()->setParam('tramitacaoTipo', 'caixaentrada');
        $this->_forward('processos-cmis');
    }

    public function caixaAnaliseAction()
    {
        $this->getRequest()->setParam('tramitacaoTipo', 'caixaanalise');
        $this->_forward('processos-cmis');
    }

    public function caixaExternoAction()
    {
        $this->getRequest()->setParam('tramitacaoTipo', 'caixaexterno');
        $this->_forward('processos-cmis');
    }

    public function processosCmisAction()
    {
        $this->view->tramitacaoTipo = $this->getRequest()->getParam('tramitacaoTipo');
    }

    public function processosGridAction()
    {
        $tramitacaoTipo = $this->getRequest()->getParam('tramitacaoTipo');

        $service = new Spu_Service_Tramitacao($this->getTicket());
        $buscaCmis = $service->getProcessosPaginatorCmis(
            $this->_helper->paginatorCmis()->getOffSet(), $this->_helper->paginatorCmis()->getMaxItems(), $tramitacaoTipo
        );

        if ($this->_helper->paginatorCmis()->getPagina() == 1)
            $this->view->totalDocumentos = $buscaCmis["totalItens"];

        $this->view->paginatorCmis = $buscaCmis["processos"];
    }

    public function receberEntradaAction()
    {
        $this->ajaxNoRender();
        $postData = array("processos" => array($this->getRequest()->getPost("processoId")));

        try {
            $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
            $tramitacaoService->receberProcesso($postData);
        } catch (Exception $exc) {
            header("HTTP/1.1 500 Internal Server Error");
            echo $exc->getMessage();
        }
    }

    public function encaminharTramitarAction()
    {
        $this->ajaxNoRender();
        $this->_setDadosAposentadoriaEncaminharAnalise();
        $postData = $this->getRequest()->getPost();
        unset($postData["processos"]);
        $postData["processo"] = $postData["idProcesso"];
        unset($postData["idProcesso"]);

        try {
            $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
            $tramitacaoService->encaminharProcesso($postData);
        } catch (Exception $exc) {
            header("HTTP/1.1 500 Internal Server Error");
            echo $exc->getMessage();
        }
    }

    public function arquivarAnaliseAction()
    {
        $this->ajaxNoRender();
        $this->_setDadosAposentadoriaArquivarAnalise();
        $postData = $this->getRequest()->getPost();
        unset($postData["processos"]);
        $postData["processos"] = array($postData["idProcesso"]);
        unset($postData["idProcesso"]);

        try {
            $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
            $tramitacaoService->arquivarVarios($postData);
        } catch (Exception $exc) {
            header("HTTP/1.1 500 Internal Server Error");
            echo $exc->getMessage();
        }
    }

    private function _setDadosAposentadoriaEncaminharAnalise()
    {
        $session = new Zend_Session_Namespace('ap');

        if (!isset($session->updateaposentadoria)) {
            $tipo = new Spu_Service_TipoProcesso($this->getTicket());
            $destino[] = $tipo->getTipoProcesso($this->_getParam('destinoId_root'))->nome;
            $destino[] = $tipo->getTipoProcesso($this->_getParam('destinoId_children'))->nome;

            $session->updateaposentadoria['ids'] = $this->_getParam('processos');
            $session->updateaposentadoria['colunas'] = array('lotacao_atual' => implode(" - ", $destino), 'status' => 'TRAMITANDO');
        }
    }

    private function _setDadosAposentadoriaArquivarAnalise()
    {
        $session = new Zend_Session_Namespace('ap');

        if (!isset($session->updateaposentadoria)) {
            $session = new Zend_Session_Namespace('ap');
            $session->updateaposentadoria['ids'] = $this->getRequest()->getPost('processos');
            $session->updateaposentadoria['colunas'] = array('status' => 'ARQUIVANDO');
        }
    }

}