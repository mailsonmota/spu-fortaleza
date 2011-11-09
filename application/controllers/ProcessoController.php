<?php
class ProcessoController extends BaseController
{
    public function detalhesAction()
    {
        try {
            $idProcesso = $this->_getIdProcessoUrl();
            $processoService = new Spu_Service_Processo($this->getTicket());
            $processo = $processoService->getProcesso($idProcesso);
            $processosParalelos = $processoService->getProcessosParalelos($processo->id);
            $arquivoService = new Spu_Service_Arquivo($this->getTicket());
            $this->view->oficioMarker = $arquivoService->getOficioUuid($processo->assunto->nodeRef);
            $this->view->diarioMarker = $arquivoService->getDiarioUuid($processo->assunto->nodeRef);
            $this->view->comunicacaoInternaMarker = $arquivoService->getComunicacaoInternaUuid($processo->assunto->nodeRef);
        } catch (Exception $e) {
            echo $e->getMessage(); exit;
            $this->setMessageForTheView('Não foi possível carregar o processo', 'error');
        }

        if (array_key_exists('etiqueta', $this->_getAllParams())) {
            $this->_redirectEtiqueta($idProcesso);
        }

        if (array_key_exists('etiqueta1', $this->_getAllParams())) {
            $this->_redirectEtiqueta($idProcesso, array('layout' => 'duplo'));
        }

        if (array_key_exists('oficio', $this->_getAllParams())) {
            $this->_helper->redirector('oficio', $this->getController(), 'default', array('id' => $idProcesso));
        }

        if (array_key_exists('diario', $this->_getAllParams())) {
            $this->_helper->redirector('diario', $this->getController(), 'default', array('id' => $idProcesso));
        }

        if (array_key_exists('comunicacao-interna', $this->_getAllParams())) {
            $this->_helper->redirector('comunicacao-interna', $this->getController(), 'default', array('id' => $idProcesso));
        }

        $this->view->processo = $processo;
        $this->view->processosParalelos = $processosParalelos;
    }

    public function encaminharAction()
    {
        try {
            $idProcesso = $this->_getIdProcessoUrl();
            $processoService = new Spu_Service_Processo($this->getTicket());
            if ($idProcesso) {
                $processo = $processoService->getProcesso($idProcesso);
            }

            $listaPrioridades = $this->_getListaPrioridades();

            $service = new Spu_Service_Protocolo($this->getTicket());
            $listaProtocolos = $service->getProtocolosRaiz();

            if ($this->getRequest()->isPost()) {
                $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
                $tramitacaoService->tramitar($this->getRequest()->getPost());
                $this->setSuccessMessage('Processo tramitado com sucesso.');
                $this->_redirectDetalhesProcesso($idProcesso);
            }
        } catch (Exception $e) {
            $this->setMessageForTheView($e->getMessage(), 'error');
        }
        $this->view->processo = $processo;
        $this->view->listaPrioridades = $listaPrioridades;
        $this->view->listaProtocolos = $listaProtocolos;
    }

    public function arquivoAction()
    {
        try {
            $arquivoHash['id'] = $this->getRequest()->getParam('id');
            $arquivoHash['nome'] = $this->getRequest()->getParam('nome');
            $arquivoService = new Spu_Service_Arquivo($this->getTicket());
            $url = $arquivoService->getArquivoDownloadUrl($arquivoHash);
            $this->getResponse()->setRedirect($url);
        } catch (Exception $e) {
            $this->setMessageForTheView($e->getMessage(), 'error');
        }
    }

    protected function _getIdProcessoUrl()
    {
        $idProcesso = $this->getRequest()->getParam('id');
        return $idProcesso;
    }

    protected function _getListaPrioridades()
    {
        $prioridadeService = new Spu_Service_Prioridade($this->getTicket());
        $prioridades = $prioridadeService->fetchAll();
        $listaPrioridades = array();
        foreach ($prioridades as $prioridade) {
            $listaPrioridades[$prioridade->id] = $prioridade->descricao;
        }

        if (count($listaPrioridades) == 0) {
            throw new Exception(
                                'Não existe nenhuma prioridade de processo cadastrada no sistema.
                Por favor, entre em contato com a administração do sistema.'
                                );
        }

        return $listaPrioridades;
    }

    protected function _redirectDetalhesProcesso($idProcesso)
    {
        $this->_helper->redirector('detalhes', $this->getController(), 'default', array('id' => $idProcesso));
    }

    protected function _redirectEtiqueta($idProcesso, array $params = array())
    {
        $this->_helper->redirector('etiqueta', $this->getController(), 'default', array('id' => $idProcesso, 'layout' => $params));
    }

    /*protected function _redirectOficio($idProcesso)
    {
        $this->_helper->redirector('oficio', $this->getController(), 'default', array('id' => $idProcesso));
    }*/

    public function etiquetaAction()
    {
        $processo = new Spu_Entity_Processo();
        try {
            $idProcesso = $this->_getIdProcessoUrl();
            $processoService = new Spu_Service_Processo($this->getTicket());
            $processo = $processoService->getProcesso($idProcesso);
        } catch (Exception $e) {
            $this->setMessageForTheView('Não foi possível carregar o processo', 'error');
        }

        $this->_helper->layout()->setLayout('relatorio');

        $this->view->processo = $processo;

        if($this->_request->getParam('layout')) {
            $this->view->layout = $this->_request->getParam('layout');
        }
    }

    public function oficioAction()
    {
        $this->_helper->layout()->disableLayout();

        $processoService = new Spu_Service_Processo($this->getTicket());
        $processo = $processoService->getProcesso($this->_getIdProcessoUrl());

        $arquivoService = new Spu_Service_Arquivo($this->getTicket());
        $arquivoString = $arquivoService->getOficioModelo($processo->assunto->nodeRef);

        try {
            $dataAtual = new Zend_Date();
            $arquivoService->substituiVariaveisEmOdt($arquivoString,
                                                     array('manifestante' => $processo->manifestante->nome,
                                                           'corpo' => $processo->corpo,
                                                           'data-abertura' => $processo->data,
                                                           'observacao' => $processo->observacao,
                                                           'tipo-processo' => $processo->tipoProcesso->nome,
                                                           'assunto' => $processo->assunto->nome,
                                                           'data-atual' => $dataAtual->toString('dd/MM/YYYY')));
        } catch (Exception $e) {
            print $e->getMessage();exit; // TODO FIXME
        }

        $this->view->arquivoString = $arquivoString;
    }

    public function diarioAction() {
        $this->_helper->layout()->disableLayout();
        
        $processoService = new Spu_Service_Processo($this->getTicket());
        $processo = $processoService->getProcesso($this->_getIdProcessoUrl());

        $arquivoService = new Spu_Service_Arquivo($this->getTicket());
        $arquivoString = $arquivoService->getDiarioModelo($processo->assunto->nodeRef);
        
        $this->view->arquivoString = $arquivoString;
    }

    public function comunicacaoInternaAction() {
        $this->_helper->layout()->disableLayout();
        
        $processoService = new Spu_Service_Processo($this->getTicket());
        $processo = $processoService->getProcesso($this->_getIdProcessoUrl());

        $arquivoService = new Spu_Service_Arquivo($this->getTicket());
        $arquivoString = $arquivoService->getComunicacaoInternaModelo($processo->assunto->nodeRef);
        
        $this->view->arquivoString = $arquivoString;
    }
}

