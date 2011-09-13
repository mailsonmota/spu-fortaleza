<?php
class Spu_Service_Processo extends Spu_Service_Abstract
{
    protected $_processoBaseUrl = 'spu/processo';
    protected $_processoTicketUrl = 'ticket';

    public function getCaixaAnaliseIncorporacao($processo)
    {
        $url = $this->getBaseUrl() . "/"
            . $this->_processoBaseUrl
            . "/incorporacaocaixaanalise"
            . "/{$processo->id}"
            . "/{$processo->assunto->id}"
            . "/{$processo->manifestante->cpf}";

        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }

    protected function _getProcessosFromUrl($url)
    {
        $result = $this->_getResultFromUrl($url);

        return $result['Processos'][0];
    }

    protected function _getResultFromUrl($url)
    {
        $result = $this->_doAuthenticatedGetRequest($url);
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }

        return $result;
    }

    public function abrirProcesso($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/abrir";

        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }

        $processo = $this->loadFromHash(array_pop(array_pop($result['Processo'][0])));
        
        return $this->_getProcessoDetalhado($processo);
    }

    protected function _getProcessoDetalhado($processo) {
        $arquivoService = new Spu_Service_Arquivo($this->getTicket());
        $processo->setRespostasFormulario($arquivoService->getRespostasFormulario($processo->id));

        $assuntoService = new Spu_Service_Assunto($this->getTicket());
        $processo->setAssunto($assuntoService->getAssunto($processo->assunto->id));

        $tipoProcessoService = new Spu_Service_TipoProcesso($this->getTicket());
        $processo->setTipoProcesso($tipoProcessoService->getTipoProcesso($processo->tipoProcesso->id));

        $processo->setArquivos($arquivoService->getArquivos($processo->id));

        $processo->setMovimentacoes($this->getHistorico($processo->id));

        return $processo;
    }

    public function getProcesso($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/get/$nodeUuid";

        $result = $this->_doAuthenticatedGetRequest($url);

        $processoHash = array_pop(array_pop($result['Processo'][0]));

        return $this->_getProcessoDetalhado($this->loadFromHash($processoHash));
    }

    public function getHistorico($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/historico/get/$nodeUuid";

        $result = $this->_doAuthenticatedGetRequest($url);
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }

        $hashProcesso = array_pop(array_pop($result['Processo'][0]));

        $hashMovimentacao = $this->_getHashValue($hashProcesso, 'movimentacoes');

        return $this->_loadMovimentacoesFromHash($hashMovimentacao);
    }

    public function incorporar($data)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/incorporar";

        $result = $this->_doAuthenticatedPostRequest($url, $data);
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }

        return $result;
    }

    public function consultar($postData, $offset = 0, $pageSize = 20)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/consultar";

        $postData['offset'] = $offset;
        $postData['pageSize'] = $pageSize;
        
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }

        return $this->_loadManyFromHash($result['Processos'][0]);
    }

    public function consultarAnexos($offset = 0, $pageSize = 20, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/consultar-conteudo/$offset/$pageSize/$filter";

        $result = $this->_doAuthenticatedGetRequest($url);
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }

        return $this->_loadManyAnexosFromHash($result['Anexos'][0]);
    }

    protected function _loadManyAnexosFromHash($hashAnexos)
    {
        $anexos = array();

        if ($hashAnexos) {
            foreach ($hashAnexos as $hashAnexo) {
                $anexos[] = $this->_loadAnexoFromHash($hashAnexo);
            }
        }

        return $anexos;
    }

    protected function _loadAnexoFromHash($hash)
    {
        $anexo = new Anexo();
        $anexo->setId($this->_getHashValue($hash, 'noderef'));
        $anexo->setNome($this->_getHashValue($hash, 'nome'));
        $anexo->setMimetype($this->_getHashValue($hash, 'mimetype'));
        $anexo->setProcesso($this->loadFromHash(array_pop(array_pop($this->_getHashValue($hash, 'processo')))));

        return $anexo;
    }

    public function getProcessosParalelos($processoId)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/paralelos/$processoId";

        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }

    public function loadFromHash($hash)
    {
        $processo = new Spu_Entity_Processo();
        $processo->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $processo->setNome($this->_getHashValue($hash, 'nome'));
        $processo->setCorpo($this->_getHashValue($hash, 'corpo'));
        $processo->setData($this->_getHashValue($hash, 'data'));
        $processo->setPrioridade($this->_loadPrioridadeFromHash($this->_getHashValue($hash, 'prioridade')));
        $processo->setStatus($this->_loadStatusFromHash($this->_getHashValue($hash, 'status')));
        $processo->setObservacao($this->_getHashValue($hash, 'observacao'));
        $processo->setNumeroOrigem($this->_getHashValue($hash, 'numeroOrigem'));
        $processo->setProtocolo($this->_loadProtocoloFromHash($this->_getHashValue($hash, 'localAtual')));
        $processo->setTipoProcesso($this->_loadTipoProcessoFromHash($this->_getHashValue($hash, 'tipoProcesso')));
        $processo->setProprietario($this->_loadProprietarioFromHash($this->_getHashValue($hash, 'proprietario')));
        $processo->setAssunto($this->_loadAssuntoFromHash($this->_getHashValue($hash, 'assunto')));
        $processo->setManifestante($this->_loadManifestanteFromHash($this->_getHashValue($hash, 'manifestante')));
        $processo->setTipoManifestante($this->_loadTipoManifestanteFromHash($this->_getHashValue($hash,
                                                                                                 'tipoManifestante')));
        $processo->setArquivamento($this->_loadArquivamentoFromHash($this->_getHashValue($hash, 'arquivamento')));
        $processo->setMovimentacoes($this->_loadMovimentacoesFromHash($this->_getHashValue($hash,
                                                                                           'ultimaMovimentacao')));
        if (!empty($hash['folhas'])) {
            $processo->setFolhas($this->_loadFolhasFromHash($hash['folhas']));
        }

        return $processo;
    }

    protected function _loadFolhasFromHash($hash)
    {
        $folhas = new Spu_Entity_Folhas();
        $folhas->setQuantidade($hash['quantidade']);

        $volumesObjectArray = array();
        foreach ($hash['volumes'] as $volumeHash) {
            $volume = new Spu_Entity_Volume();
            $volume->setNome($volumeHash['nome']);
            $volume->setInicio($volumeHash['inicio']);
            $volume->setFim($volumeHash['fim']);
            $volume->setObservacao($volumeHash['observacao']);
            $volumesObjectArray[] = $volume;
        }

        $folhas->setVolumes($volumesObjectArray);
        return $folhas;
    }

    protected function _loadPrioridadeFromHash($hash)
    {
        $hash = array_pop($hash);
        $prioridadeService = new Spu_Service_Prioridade($this->getTicket());
        $prioridade = $prioridadeService->loadFromHash($hash);

        return $prioridade;
    }

    protected function _loadStatusFromHash($hash)
    {
        $hash = array_pop($hash);
        $statusService = new Spu_Service_Status($this->getTicket());
        $status = $statusService->loadFromHash($hash);

        return $status;
    }

    protected function _loadProtocoloFromHash($hash)
    {
        $hash = array_pop($hash);
        $protocoloService = new Spu_Service_Protocolo($this->getTicket());
        $protocolo = $protocoloService->loadFromHash($hash);

        return $protocolo;
    }

    protected function _loadTipoProcessoFromHash($hash)
    {
        $hash = array_pop($hash);
        $tipoProcesso = new Spu_Entity_TipoProcesso($this->getTicket());
        $tipoProcesso->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $tipoProcesso->setNome($this->_getHashValue($hash, 'nome'));

        return $tipoProcesso;
    }

    protected function _loadProprietarioFromHash($hash)
    {
        return $this->_loadProtocoloFromHash($hash);
    }

    protected function _loadAssuntoFromHash($hash)
    {
        $hash = array_pop($hash);
        $assuntoService = new Spu_Service_Assunto($this->getTicket());
        $assunto = $assuntoService->loadFromHash($hash);

        return $assunto;
    }

    protected function _loadManifestanteFromHash($hash)
    {
        $hash = array_pop($hash);
        $manifestanteService = new Spu_Service_Manifestante($this->getTicket());
        $manifestante = $manifestanteService->loadFromHash($hash);

        return $manifestante;
    }

    protected function _loadTipoManifestanteFromHash($hash)
    {
        $hash = array_pop($hash);
        $tipoManifestanteService = new Spu_Service_TipoManifestante($this->getTicket());
        $tipoManifestante = $tipoManifestanteService->loadFromHash($hash);

        return $tipoManifestante;
    }

    protected function _loadArquivamentoFromHash($hash)
    {
        $hash = array_pop($hash);
        $arquivamentoService = new Spu_Service_Arquivamento();
        $arquivamento = $arquivamentoService->loadFromHash($hash);

        return $arquivamento;
    }

    protected function _loadMovimentacoesFromHash($hash)
    {
        $movimentacaoService = new Spu_Service_Movimentacao();
        return $movimentacaoService->loadManyFromHash($hash);
    }

    protected function _loadManyFromHash($hashProcessos)
    {
        $processos = array();

        if ($hashProcessos) {
            foreach ($hashProcessos as $hashProcesso) {
                $hashDadosProcesso = array_pop($hashProcesso);
                $processos[] = $this->loadFromHash($hashDadosProcesso);
            }
        }

        return $processos;
    }
}