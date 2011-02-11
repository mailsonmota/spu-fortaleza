<?php
require_once('BaseService.php');
Loader::loadEntity('Processo');
Loader::loadService('TipoProcessoService');
Loader::loadService('PrioridadeService');
Loader::loadService('StatusService');
Loader::loadService('ProtocoloService');
Loader::loadService('TipoManifestanteService');
Loader::loadService('ArquivamentoService');
Loader::loadService('MovimentacaoService');
Loader::loadService('AssuntoService');
class ProcessoService extends BaseService
{
    private $_processoBaseUrl = 'spu/processo';
    private $_processoTicketUrl = 'ticket';
    
    public function getCaixaEntrada($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/entrada/$offset/$pageSize/$filter";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaSaida($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/saida/$offset/$pageSize/$filter";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaAnalise($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/analise/$offset/$pageSize/$filter";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaEnviados($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/enviados/$offset/$pageSize/$filter";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaExternos()
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/externos";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaArquivo($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivo/$offset/$pageSize/$filter";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    protected function _getProcessosFromUrl($url)
    {
        $result = $this->_getResultFromUrl($url);
        
        return $result['Processos'][0];
    }
    
    protected function _getResultFromUrl($url)
    {
        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
    
    public function abrirProcesso($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/abrir";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        $processo = $this->loadFromHash(array_pop(array_pop($result['Processo'][0])));
        
        return $this->_getProcessoDetalhado($processo);
    }
    
    protected function _getProcessoDetalhado($processo) {
    	$arquivoService = new ArquivoService($this->getTicket());
        $processo->setRespostasFormulario($arquivoService->getRespostasFormulario($processo->id));
            
        $tipoProcessoService = new TipoProcessoService($this->getTicket());
        $processo->setTipoProcesso($tipoProcessoService->getTipoProcesso($processo->tipoProcesso->id));
        
        $processo->setArquivos($arquivoService->getArquivos($processo->id));
        
        $processo->setMovimentacoes($this->getHistorico($processo->id));
        
        return $processo;
    }
    
    public function getProcesso($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);
        
        $processoHash = array_pop(array_pop($result['Processo'][0])); 
        
        $processo = $this->_getProcessoDetalhado($this->loadFromHash($processoHash));
        
        return $processo;
    }
    
    public function tramitar($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/tramitar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
    
    public function tramitarVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/tramitarProcessos";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
    
    public function receberVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/receber";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
    
    public function tramitarExternos($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/tramitarExternos";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
    
    public function retornarExternos($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/retornarExternos";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
    
    public function getHistorico($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/historico/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        $hashProcesso = array_pop(array_pop($result['Processo'][0]));
        
        $hashMovimentacao = $this->_getHashValue($hashProcesso, 'movimentacoes');
        
        return $this->_loadMovimentacoesFromHash($hashMovimentacao);
    }
    
    public function cancelarEnvios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/cancelarEnvios";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
    
    public function incorporar($data)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/incorporar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doPostRequest($url, $data);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
    
    public function arquivarVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
    
    public function reabrirVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/reabrir";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
    
    public function comentarVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/comentar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
    
    public function consultar($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/consultar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $this->_loadManyFromHash($result['Processos'][0]);
    }
    
    public function getProcessosParalelos($processoId)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/paralelos/$processoId";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_getProcessosFromUrl($url);
    }
    
    public function loadFromHash($hash)
    {
    	$processo = new Processo();
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
        
        return $processo;
    }
    
    protected function _loadPrioridadeFromHash($hash)
    {
        $hash = array_pop($hash);
        $prioridadeService = new PrioridadeService($this->getTicket());
        $prioridade = $prioridadeService->loadFromHash($hash);
        
        return $prioridade;
    }
    
    protected function _loadStatusFromHash($hash)
    {
        $hash = array_pop($hash);
        $statusService = new StatusService($this->getTicket());
        $status = $statusService->loadFromHash($hash);
        
        return $status;
    }
    
    protected function _loadProtocoloFromHash($hash)
    {
        $hash = array_pop($hash);
        $protocoloService = new ProtocoloService($this->getTicket());
        $protocolo = $protocoloService->loadFromHash($hash);
        
        return $protocolo;
    }
    
    protected function _loadTipoProcessoFromHash($hash)
    {
        $hash = array_pop($hash);
        $tipoProcesso = new TipoProcesso($this->getTicket());
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
        $assuntoService = new AssuntoService($this->getTicket());
        $assunto = $assuntoService->loadFromHash($hash);
        
        return $assunto;
    }
    
    protected function _loadManifestanteFromHash($hash)
    {
        $hash = array_pop($hash);
        $manifestanteService = new ManifestanteService($this->getTicket());
        $manifestante = $manifestanteService->loadFromHash($hash);
        
        return $manifestante;
    }
    
    protected function _loadTipoManifestanteFromHash($hash)
    {
        $hash = array_pop($hash);
        $tipoManifestanteService = new TipoManifestanteService($this->getTicket());
        $tipoManifestante = $tipoManifestanteService->loadFromHash($hash);
        
        return $tipoManifestante;
    }
    
    protected function _loadArquivamentoFromHash($hash)
    {
        $hash = array_pop($hash);
        $arquivamentoService = new ArquivamentoService();
        $arquivamento = $arquivamentoService->loadFromHash($hash);
        
        return $arquivamento;
    }
    
    protected function _loadMovimentacoesFromHash($hash)
    {
    	$movimentacaoService = new MovimentacaoService();
    	return $movimentacaoService->loadManyFromHash($hash);
    }
    
    protected function _loadManyFromHash($hashProcessos)
    {
        $processos = array();
        foreach ($hashProcessos as $hashProcesso) {
            $hashDadosProcesso = array_pop($hashProcesso); 
            $processos[] = $this->loadFromHash($hashDadosProcesso);
        }
        
        return $processos;
    }
}