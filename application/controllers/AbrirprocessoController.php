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
        $this->view->listaOrigens = array_diff_key($listaOrigens, array_flip($this->getExcludeNodeRed()));
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
            $listaProtocolos = $this->removeProtocolos($dados['Protocolos']);
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

            $formDadosGeraisProcesso = $session->formDadosGeraisProcesso;
            $postData = $this->getRequest()->getPost();
            
            $dataMerged = array_merge($formDadosGeraisProcesso, $postData);
            $processoService = new Spu_Service_Processo($this->getTicket());
            $dataMerged["nrProcesso"] = "";
            
            try {
//                $processo = $processoService->abrirProcesso($this->filterValuesArray($dataMerged));
                $processo = $processoService->criarProcesso($this->filterValuesArray($dataMerged));
            } catch (Exception $e) {
                $this->setErrorMessage('Erro ao abrir o processo. Informação técnica: ' . $e->getMessage());
                $this->_redirectFormularioEnvolvido();
            }

            $session->processo = $processo;

            if ($processo->assunto->hasFormulario()) {
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
        $processo = $session->processo;

        if ($this->getRequest()->isPost()) {
            // Limpeza da lista de arquivos utilizada pelo próximo passo na Abertura de Processo
            unset($session->filesToUpload);
            $postData = $this->getRequest()->getPost();

            $session->prontuario = reset($this->getRequest()->getPost());
            try {
                $arquivoService = new Spu_Service_Arquivo($this->getTicket());
                $arquivoService->salvarFormulario($postData);
                $this->_redirectUploadArquivo();
            } catch (AlfrescoApiException $e) {
                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
        }

        $processoService = new Spu_Service_Processo($this->getTicket());
        $session->processo = $processoService->getProcesso($processo->id);

        if (!$processo->assunto->hasFormulario()) {
            $this->_helper->redirector('uploadarquivo');
        }

        $this->view->processoId = $processo->id;
        $this->view->assuntoId = $processo->assunto->id;
    }

    public function uploadarquivoAction()
    {
        $session = new Zend_Session_Namespace('aberturaProcesso');
        $processo = $session->processo;
        $this->view->processoUuid = $session->processo->id;
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

                try {
                    // Itera arquivos escolhidos, adicionando-os ao processo
                    foreach ($session->filesToUpload as $fileToUpload) {
                        $postData['fileToUpload'] = $fileToUpload['filePath'];
                        $postData = array_merge($postData, $fileToUpload);

                        $arquivoService = new Spu_Service_Arquivo($this->getTicket());
                        // TODO Pesquisar sobre unlink($arquivo)
                        $arquivoService->uploadArquivo($postData);
                    }

                    // Limpeza da lista de arquivos
                    unset($session->filesToUpload);
                } catch (Exception $e) {
                    throw new Exception('Erro no upload de arquivo. Mensagem: ' . $e->getMessage());
                }

                $this->_redirectConfirmacaoCriacao();
            }
        }

        // Recarregando o processo para pegar os arquivos recém-anexados
        $processoService = new Spu_Service_Processo($this->getTicket());
        $processo = $processoService->getProcesso($processo->id);

        $this->view->hasFormulario = $processo->assunto->hasFormulario();
        $this->view->uploadedFiles = $processo->getArquivos();
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
            $session = new Zend_Session_Namespace('aberturaProcesso');
            $processo = $session->processo;
            $postData['processoId'] = $processo->id;
            $postData['destinoId'] = $session->formDadosGeraisProcesso['destino'];
            $postData['prioridadeId'] = $processo->prioridade->id;
            $postData['prazo'] = $processo->data;
            $postData['despacho'] = "";
            $postData['tipoMovimentacao'] = "ABERTURA";
            $postData['copias'] = $session->formDadosGeraisProcesso['copias'];

            $session_ap = new Zend_Session_Namespace('ap');
            $session_ap->insertaposentadoria['dados'] = array('id' => $processo->id, 'prontuario' => $session->prontuario);

            try {
                $tramitacao = new Spu_Service_Tramitacao($this->getTicketSearch());
                $idParent = "workspace://SpacesStore/" . $postData['destinoId'];
                $postData["caixaEntradaId"] = substr($tramitacao->getIdFolderCmis($idParent, "caixaentrada"), 24);

                $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
                $tramitacaoService->finalizarAbertura($postData);
            } catch (AlfrescoApiException $e) {
                throw $e;
            } catch (Exception $e) {
                throw $e;
            }

            $this->_redirectProcessoDetalhes($processo->id);
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

	/*$exclude_id = array(
            "9cbc854a-f13a-4206-8ee9-5ce45ac371fe",
            "cb97e228-46a2-4a40-a080-7d52479218e3",
            "d7b52d58-847e-49e2-8201-f74f5bd949ea",
            "4c69829c-431b-49f2-83e3-4ed61ea7a2e3",
            "1afc2def-b116-427b-80f2-81d2af6f2e39",
            "3f564c5f-2faf-4a5e-8d92-a6e3d99ff923",
            "8139ed82-59cd-4da9-9181-6b0cdb2f62ca",
            "0f8afb3a-aecb-404a-8f76-664e33d92948",
            "f8027314-e0fd-4abb-8cec-a2f20d5f0537",
            "b4d690a7-00fc-4202-ae50-62cf798e3669",
            "726877fd-2e08-44e6-98da-3887e09efc50",
            "c807e0f0-b394-45ce-807e-f9637a1c5ee2",
            "7118d649-33c5-4947-a941-5c44f1c66c0f",
            "1c3fcf02-62e1-4fef-bcbb-3cb3eb498e62",
            "20f0f1ae-5fef-4859-a3cc-4c4b9305f21f",
            "2c16a2ad-da64-44c7-b0aa-197985258f8d",
            "29bb51f6-42ba-4f65-9fd4-0a6293ae6751",
            "20ec1030-70cd-41c1-84f5-b67a004af4e7",
            "7c7f40b7-14df-4999-be1b-5852178815cb",
            "0924c01e-17c9-4b84-800c-1f9ac8f83c95",
            "256f9a8f-971b-454a-8538-471584bea175",
            "81bcde77-c8f6-448e-aeae-aab6cd7fa7fc",
            "bf3414d8-0b2a-4e6a-a110-2a25e0ef8401",
            "1e6fe2e6-83a5-4bc0-8a9b-895d2fd232a0",
            "796b92c1-c750-4f1b-bc52-3e54101ff177",
            "d5928994-6d0b-4864-bf91-7268ec26d337",
            "fb5d5159-d554-4728-ac85-0efa36354876",
            "7a3d9452-f9f0-48eb-80d5-1acf027b1e6c",
            "be4bae5d-f659-4990-976c-762d24fea51a",
            "a790a3a3-ca9d-40da-b75f-d94b42d3272f",
            "845a6e0f-f9bb-404d-bee3-76e53d3cacda",
            "4ad7d13e-0386-470a-946b-3705b04b8aa1",
            "1cfb653b-dc75-4a91-a2c0-7b5a9d681d37",
            "011eb7e9-2be2-4087-8c66-c9ac1cee21b1",
            "684fac0c-6e3e-43de-b1ff-551edb6cb2b9",
            "1d0157fd-412d-4f7c-b595-eafb8d9c4080",
            "a58b86b9-18fd-46cf-b829-83ff5cc4f56c",
            "9c303165-084e-4279-8f8c-56921aa8ad80",
            "48566614-f4eb-4040-aa7f-9a358bfe6078",
            "7842bb5d-b0cb-428d-8336-2585d902c9af"
        );
        $listaAssuntos = array_diff_key($listaAssuntos, array_flip($exclude_id));
*/
        return $this->_ordenarListaAssuntos($listaAssuntos);
    }

    private function _ordenarListaAssuntos(array $lista)
    {
        asort($lista);

        return $lista;
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
        asort($listaBairros);
        
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
