<?php

class AbrirprocessoController extends BaseController
{

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->_helper->redirector('formulario', $this->getController(), 'default', array('protocoloOrigem' => $this->_getIdProtocoloOrigemRequestParam(),
                'tipoprocesso' => $this->_getIdTipoProcessoPost()));
        }

        $listaOrigens = $this->_getListaOrigens();

        $listaTiposProcesso = $this->_getListaTiposProcesso($this->_getIdOrigemPreferencial($listaOrigens));

        if (!$listaTiposProcesso) {
            $this->setMessageForTheView('Não será possível abrir nenhum processo, pois você não tem 
        	                              acesso à nenhum Tipo de Processo.');
        }

        $this->view->listaTiposProcesso = $listaTiposProcesso;
        $this->view->listaOrigens = $listaOrigens;
    }

    protected function _getIdOrigemPreferencial($listaOrigens)
    {
        return (is_array($listaOrigens)) ? key($listaOrigens) : null;
    }

    public function formularioAction()
    {

        if ($this->getRequest()->isPost()) {

            $postData = $this->getRequest()->getParams();

            try {
                $session = new Zend_Session_Namespace('aberturaProcesso');
                $postData['proprietarioId'] = $postData['protocoloOrigem'];
                $session->formDadosGeraisProcesso = $postData;
                $this->_redirectFormularioEnvolvido($this->_getIdTipoProcessoUrl());
            } catch (AlfrescoApiException $e) {
                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
        }

        try {
            $protocoloOrigemId = $this->_getIdProtocoloOrigemUrl();
            $servico = new Spu_Service_Processo($this->getTicket());
            $tipoProcesso = $this->_getTipoProcesso($this->_getIdTipoProcessoUrl());
            $dados = $servico->getDadosPassoDois($this->_getIdTipoProcessoUrl());
            $listaProtocolos = $dados['Protocolos'];
            $listaAssuntos = $this->_getListaAssuntos(null, $dados['Assuntos']);
            $listaBairros = $this->_getListaBairros($dados['Bairros']);
            $listaTiposManifestante = $this->_getListaTiposManifestante(null, $dados['Manifestantes']);
            $listaPrioridades = $this->_getListaPrioridades($dados['Prioridades']);
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectEscolhaTipoProcesso();
        }


        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->listaAssuntos = $listaAssuntos;
        $this->view->listaBairros = $listaBairros;
        $this->view->listaTiposManifestante = $listaTiposManifestante;
        $this->view->listaPrioridades = $listaPrioridades;
        $this->view->protocoloOrigemId = $protocoloOrigemId;
        $this->view->listaProtocolos = $listaProtocolos;
    }

//    public function formularioAction()
//    {
//        
//        if ($this->getRequest()->isPost()) {
//
//            $postData = $this->getRequest()->getParams();
//
//            try {
//                $session = new Zend_Session_Namespace('aberturaProcesso');
//                $postData['proprietarioId'] = $postData['protocoloOrigem'];
//                $session->formDadosGeraisProcesso = $postData;
//                $this->_redirectFormularioEnvolvido($this->_getIdTipoProcessoUrl());
//            } catch (AlfrescoApiException $e) {
//                throw $e;
//            } catch (Exception $e) {
//                throw $e;
//            }
//        }
//        
//        try {
//            $protocoloOrigemId = $this->_getIdProtocoloOrigemUrl();
//            $servico = new Spu_Service_Processo($this->getTicket());
//            $dados = $servico->getDadosPassoDois($this->_getIdTipoProcessoUrl());
//            $tipoProcesso = $this->_getTipoProcesso($this->_getIdTipoProcessoUrl());
//            $service = new Spu_Service_Protocolo($this->getTicket());
//            $listaProtocolos = $service->getProtocolosRaiz($protocoloOrigemId, $tipoProcesso->id);
//            $listaAssuntos = $this->_getListaAssuntos($tipoProcesso);
//            $listaBairros = $this->_getListaBairros();
//            $listaTiposManifestante = $this->_getListaTiposManifestante($tipoProcesso);
//            $listaPrioridades = $this->_getListaPrioridades();
//        } catch (Exception $e) {
//            $this->setErrorMessage($e->getMessage());
//            $this->_redirectEscolhaTipoProcesso();
//        }
//
//        
//        $this->view->tipoProcesso = $tipoProcesso;
//        $this->view->listaAssuntos = $listaAssuntos;
//        $this->view->listaBairros = $listaBairros;
//        $this->view->listaTiposManifestante = $listaTiposManifestante;
//        $this->view->listaPrioridades = $listaPrioridades;
//        $this->view->protocoloOrigemId = $protocoloOrigemId;
//        $this->view->listaProtocolos = $listaProtocolos;
//    }

    public function formularioenvolvidoAction()
    {
        $tipoProcesso = $this->_getTipoProcesso($this->_getIdTipoProcessoUrl());
        $listaBairros = $this->_getListaBairros();
        $listaTiposManifestante = $this->_getListaTiposManifestante($tipoProcesso);
        $listaUfs = $this->_getListaUfs();
        if ($this->getRequest()->isPost()) {
            $session = new Zend_Session_Namespace('aberturaProcesso');

            // Limpeza da lista de arquivos utilizada pelo próximo passo na Abertura de Processo
            unset($session->filesToUpload);

//            $formDadosGeraisProcesso = $session->formDadosGeraisProcesso;
//            $postData = $this->getRequest()->getPost();
            $session->formDadosGeraisManifestante = $this->getRequest()->getPost();

//            $dataMerged = array_merge($formDadosGeraisProcesso, $postData);

            try {
                $processoService = new Spu_Service_Processo($this->getTicket());
//                $processo = $processoService->abrirProcesso($this->filterValuesArray($dataMerged));
                $hasFormulario = $processoService->hasFormulario($session->formDadosGeraisProcesso["assunto"]);
            } catch (Exception $e) {
                $this->setErrorMessage('Erro ao abrir o processo. Informação técnica: ' . $e->getMessage());
                $this->_redirectFormularioEnvolvido();
            }

//            $session->processo = $processo;
            if ($hasFormulario) {
                $this->_redirectFormularioAssunto();
            } else {
                $this->_redirectUploadArquivo();
            }
        }

        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->listaBairros = $listaBairros;
        $this->view->listaTiposManifestante = $listaTiposManifestante;
        $this->view->listaUfs = $listaUfs;
    }

    protected function _getListaUfs()
    {
        return array(
            'CE' => 'CE',
            'AC' => 'AC',
            'AL' => 'AL',
            'AM' => 'AM',
            'AP' => 'AP',
            'BA' => 'BA',
            'DF' => 'DF',
            'ES' => 'ES',
            'GO' => 'GO',
            'MA' => 'MA',
            'MG' => 'MG',
            'MS' => 'MS',
            'MT' => 'MT',
            'PA' => 'PA',
            'PB' => 'PB',
            'PE' => 'PE',
            'PI' => 'PI',
            'PR' => 'PR',
            'RJ' => 'RJ',
            'RN' => 'RN',
            'RO' => 'RO',
            'RR' => 'RR',
            'RS' => 'RS',
            'SC' => 'SC',
            'SE' => 'SE',
            'SP' => 'SP',
            'TO' => 'TO'
        );
    }

    public function formularioAssuntoAction()
    {
        $session = new Zend_Session_Namespace('aberturaProcesso');
//        $processo = $session->processo;

        if ($this->getRequest()->isPost()) {
            // Limpeza da lista de arquivos utilizada pelo próximo passo na Abertura de Processo
            unset($session->filesToUpload);
//            $postData = $this->getRequest()->getPost();
            $session->formularioAssuntoRespostas = $this->getRequest()->getPost();
            $session->prontuario = reset($this->getRequest()->getPost());
            $this->_redirectUploadArquivo();
//            try {
//                $arquivoService = new Spu_Service_Arquivo($this->getTicket());
//                $arquivoService->salvarFormulario($postData);
//            } catch (AlfrescoApiException $e) {
//                throw $e;
//            } catch (Exception $e) {
//                throw $e;
//            }
        }

//        $processoService = new Spu_Service_Processo($this->getTicket());
//        $session->processo = $processoService->getProcesso($processo->id);
//
//        if (!$processo->assunto->hasFormulario()) {
//            $this->_helper->redirector('uploadarquivo');
//        }
//        
//        $this->view->processoId = $processo->id;
        $this->view->assuntoId = $session->formDadosGeraisProcesso["assunto"];
    }

    public function uploadarquivoAction()
    {
        $session = new Zend_Session_Namespace('aberturaProcesso');
        //$processo = $session->processo;
        //$this->view->processoUuid = $session->processo->id;
        $this->view->ticket = $this->getTicket();

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();

            if (!empty($_FILES)) {
                if (empty($_FILES['fileToUpload']['tmp_name'])) {
                    $this->setErrorMessage('Não foi possível enviar o arquivo. Talvez o tamanho seja maior que o máximo permitido.');
                    $this->_redirectUploadArquivo();
                }

                // Rotina de adição de arquivo
                $fileTmp = array('filePath' => $this->_uploadFilePathConverter($_FILES['fileToUpload']['name'], $_FILES['fileToUpload']['tmp_name']),
                    'fileType' => $_FILES['fileToUpload']['type'],
                    'tipoDocumento' => $this->_getParam('tipo_documento'));

                if (!empty($session->filesToUpload)) {
                    foreach ($session->filesToUpload as $fileToUpload) {
                        if ($fileToUpload['filePath'] == $fileTmp['filePath']) {
                            $this->setErrorMessage('Este arquivo já se encontra na lista de arquivos a ser submetida.');
                            $this->_redirectUploadArquivo();
                        }
                    }
                }
                /* filesToUpload[] vai receber array, e nao mais string.
                  array(file => $fileTmp, tipo-documento => node_ref de tipo de documento, como 'comprovante de pagamento') */
                $session->filesToUpload[] = $fileTmp;
            } else {

//                try {
//                    // Itera arquivos escolhidos, adicionando-os ao processo
//                    foreach ($session->filesToUpload as $fileToUpload) {
//                        $postData['fileToUpload'] = $fileToUpload['filePath'];
//                        $postData = array_merge($postData, $fileToUpload);
//
//                        $arquivoService = new Spu_Service_Arquivo($this->getTicket());
//                        // TODO Pesquisar sobre unlink($arquivo)
//                        $arquivoService->uploadArquivo($postData);
//                    }
//
//                    // Limpeza da lista de arquivos
//                    unset($session->filesToUpload);
//                } catch (Exception $e) {
//                    throw new Exception('Erro no upload de arquivo. Mensagem: ' . $e->getMessage());
//                }

                $this->_redirectConfirmacaoCriacao();
            }
        }

        // Recarregando o processo para pegar os arquivos recém-anexados
//        $processoService = new Spu_Service_Processo($this->getTicket());
//        $processo = $processoService->getProcesso($processo->id);

        $processoService = new Spu_Service_Processo($this->getTicket());
        $hasFormulario = $processoService->hasFormulario($session->formDadosGeraisProcesso["assunto"]);
        $this->view->hasFormulario = $hasFormulario;
        //$this->view->uploadedFiles = $processo->getArquivos();
        $this->view->filesToUpload = $session->filesToUpload;

        $tipoDocumentoService = new Spu_Service_TipoDocumento($this->getTicket());

        $selectOptions[''] = 'Selecione um tipo';
        foreach ($tipoDocumentoService->getTiposDocumentos() as $tipoDocumento) {
            $selectOptions[$tipoDocumento->nodeRef] = $tipoDocumento->nome;
        }
        $this->view->tiposDocumentosSelectOptions = $selectOptions;

        $this->view->serviceTipoDocumento = new Spu_Service_TipoDocumento($this->getTicket());
    }

    public function removerarquivoAction()
    {
        $session = new Zend_Session_Namespace('aberturaProcesso');
        $numero = $this->getRequest()->getParam('removerarquivo');
        unset($session->filesToUpload[$numero]);
        $session->filesToUpload = array_values($session->filesToUpload);
        $this->setSuccessMessage('Arquivo removido da lista de arquivos a ser submetida.');
        $this->_redirectUploadArquivo();
    }

    public function confirmacaocriacaoAction()
    {
        $session = new Zend_Session_Namespace('aberturaProcesso');
        $protocoloService = new Spu_Service_Protocolo($this->getTicket());
        $protocoloOrigem = $protocoloService->getProtocolo($session->formDadosGeraisProcesso['protocoloOrigem']);
        $this->view->origemNome = $protocoloOrigem->nome;

        if ($this->getRequest()->isPost()) {
            $dadosFormulario = array_merge($session->formDadosGeraisProcesso, $session->formDadosGeraisManifestante);
            $processoService = new Spu_Service_Processo($this->getTicket());
            $processo = $processoService->abrirProcesso($this->filterValuesArray($dadosFormulario));
            
            $postData['processoId'] = $processo->id;
            $postData['destinoId'] = $session->formDadosGeraisProcesso['destino'];
            $postData['prioridadeId'] = $processo->prioridade->id;
            $postData['prazo'] = $processo->data;
            $postData['despacho'] = "";
            $postData['copias'] = $session->formDadosGeraisProcesso['copias'];

            $session_ap = new Zend_Session_Namespace('ap');
            $session_ap->insertaposentadoria['dados'] = array('id' => $processo->id, 'prontuario' => $session->prontuario);

            try {
                $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
                $tramitacaoService->tramitar($postData);
            } catch (AlfrescoApiException $e) {
                throw $e;
            } catch (Exception $e) {
                throw $e;
            }

            $this->_redirectProcessoDetalhesAbertura($processo->id);
        }
    }

    public function processocriadoAction()
    {
        $defaultNamespaceSession = new Zend_Session_Namespace('aberturaProcesso');
        $this->view->processo = $defaultNamespaceSession->processo;
    }

    protected function _getIdTipoProcessoPost()
    {
        return ($this->getRequest()->getParam('tipoprocesso')) ? $this->getRequest()->getParam('tipoprocesso') : null;
    }

    protected function _getIdProtocoloOrigemRequestParam()
    {
        return ($this->getRequest()->getParam('origem')) ? $this->getRequest()->getParam('origem') : null;
    }

    protected function _getListaTiposProcesso($origemId)
    {
        $tipoProcessoService = new Spu_Service_TipoProcesso($this->getTicket());
        $tiposProcesso = $tipoProcessoService->getTiposProcesso($origemId);
        $listaTiposProcesso = array();
        foreach ($tiposProcesso as $tipoProcesso) {
            $listaTiposProcesso[$tipoProcesso->id] = $tipoProcesso->nome;
        }

        return $listaTiposProcesso;
    }

    protected function _redirectProcessoDetalhes($uuid)
    {
        $this->_helper->redirector('detalhes', 'processo', 'default', array('id' => $uuid));
    }
    
    protected function _redirectProcessoDetalhesAbertura($uuid)
    {
        $this->_helper->redirector('detalhes-abertura', 'processo', 'default', array('id' => $uuid));
    }

    protected function _getIdTipoProcessoUrl()
    {
        $idTipoProcesso = $this->getRequest()->getParam('tipoprocesso');
        return $idTipoProcesso;
    }

    protected function _getIdProtocoloOrigemUrl()
    {
        return $this->getRequest()->getParam('protocoloOrigem', null);
    }

    protected function _getTipoProcesso($idTipoProcesso = null)
    {
        $tipoProcessoService = new Spu_Service_TipoProcesso($this->getTicket());
        if ($idTipoProcesso) {
            $tipoProcesso = $tipoProcessoService->getTipoProcesso($idTipoProcesso);
        } else {
            $tipoProcesso = new Spu_Entity_TipoProcesso();
        }

        return $tipoProcesso;
    }

    protected function _getProtocolo($protocoloId = null)
    {
        $protocoloService = new Spu_Service_Protocolo($this->getTicket());
        if ($protocoloId) {
            $protocolo = $protocoloService->getProtocolo($protocoloId);
        } else {
            $protocolo = new Spu_Entity_Protocolo();
        }
        return $protocolo;
    }

    protected function _getListaAssuntos(Spu_Entity_TipoProcesso $tipoProcesso = null, $assuntos = null)
    {
        if (null === $assuntos) {
            $assuntoService = new Spu_Service_Assunto($this->getTicket());
            $assuntos = $assuntoService->getAssuntosPorTipoProcesso($tipoProcesso->getId());
        }

        $listaAssuntos = array();
        foreach ($assuntos as $assunto) {
            $listaAssuntos[$assunto->id] = $assunto->nome;
        }

        if (count($listaAssuntos) == 0) {
            throw new Exception(
                'O tipo de processo selecionado não possui nenhum assunto. Por favor, escolha outro.'
            );
        }

        return $listaAssuntos;
    }

    protected function _getListaTiposManifestante($tipoProcesso = null, $tiposManifestante = null)
    {
        if (null === $tiposManifestante) {
            $tiposManifestante = $tipoProcesso->getTiposManifestante();
        }

        if (!$tiposManifestante) {
            $serviceTiposManifestante = new Spu_Service_TipoManifestante($this->getTicket());
            $tiposManifestante = $serviceTiposManifestante->fetchAll();
        }

        $listaTiposManifestante = array();
        foreach ($tiposManifestante as $tipoManifestante) {
            $listaTiposManifestante[$tipoManifestante->id] = $tipoManifestante->descricao;
        }

        if (count($listaTiposManifestante) == 0) {
            throw new Exception(
                'Não existe nenhum tipo de manifestante cadastrado no sistema.
                Por favor, entre em contato com a administração do sistema.'
            );
        }

        return $listaTiposManifestante;
    }

    protected function _getListaBairros($bairros = null)
    {
        if (null === $bairros) {
            $bairroService = new Spu_Service_Bairro($this->getTicket());
            $bairros = $bairroService->getBairros();
        }

        $listaBairros = array();
        foreach ($bairros as $bairro) {
            $listaBairros[$bairro->id] = $bairro->descricao;
        }

        if (count($listaBairros) == 0) {
            throw new Exception(
                'Não existe nenhum bairro cadastrado no sistema.
                Por favor, entre em contato com a administração do sistema.'
            );
        }

        return $listaBairros;
    }

    protected function _getListaPrioridades($prioridades = null)
    {
        if (null === $prioridades) {
            $prioridadeService = new Spu_Service_Prioridade($this->getTicket());
            $prioridades = $prioridadeService->fetchAll();
        }

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

    protected function _getListaOrigens()
    {
        $protocoloService = new Spu_Service_Protocolo($this->getTicket());
        $protocolos = $protocoloService->getProtocolos();
        $listaProtocolos = array();
        foreach ($protocolos as $protocolo) {
            $listaProtocolos[$protocolo->id] = $protocolo->path;
        }

        if (count($listaProtocolos) == 0) {
            throw new Exception(
                'Você não pode enviar nenhum processo, pois não possui acesso à nenhum protocolo.'
            );
        }

        return $listaProtocolos;
    }

    protected function _redirectEscolhaTipoProcesso()
    {
        $this->_helper->redirector('index', $this->getController(), 'default');
    }

    protected function _redirectProcessoCriado()
    {
        $this->_helper->redirector('processocriado', $this->getController(), 'default');
    }

    protected function _redirectFormularioAssunto()
    {
        $this->_helper->redirector('formulario-assunto', $this->getController(), 'default');
    }

    protected function _redirectUploadArquivo()
    {
        $this->_helper->redirector('uploadarquivo', $this->getController(), 'default');
    }

    protected function _redirectConfirmacaoCriacao()
    {
        $this->_helper->redirector('confirmacaocriacao', $this->getController(), 'default');
    }

    protected function _redirectFormularioEnvolvido()
    {
        $this->_helper->redirector('formularioenvolvido', $this->getController(), 'default', array('tipoprocesso' => $this->_getIdTipoProcessoUrl()));
    }

}
