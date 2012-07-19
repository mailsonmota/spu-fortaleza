<?php

/**
 * Classe para acessar os serviços de Processo do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_Processo extends Spu_Service_Abstract
{

    /**
     * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
     * @var string
     */
    protected $_processoBaseUrl = 'spu/processo';
    
    
    public function getDadosPassoDois($nodeRef)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/abrir/passodois/" . $nodeRef;
        
        $name = $this->getNameForMethod('getDadosPassoDois', $nodeRef);
        if (($result = $this->getCache()->load($name)) === false) {

            $result = $this->_doAuthenticatedGetRequest($url);

            $this->getCache()->save($result, $name);
        }
        
        $dados = array();

        $serviceProtocolos = new Spu_Service_Protocolo();
        $dados['Protocolos'] = $serviceProtocolos->_loadManyFromHash($result['protocolos']);
        
        $serviceAssuntos = new Spu_Service_Assunto();
        $dados['Assuntos'] = $serviceAssuntos->_loadManyFromHash($result['assuntos']);
        
        $serviceBairros = new Spu_Service_Bairro();
        $dados['Bairros'] = $serviceBairros->_loadManyFromHash($result['bairros']);
        
        $serviceManifestantes = new Spu_Service_TipoManifestante();
        $dados['Manifestantes'] = $serviceManifestantes->_loadManyFromHash($result['tiposManifestantes'][0]);
        
        $servicePrioridades = new Spu_Service_Prioridade();
        $dados['Prioridades'] = $servicePrioridades->_loadManyFromHash($result['prioridades'][0]);
        
        return $dados;
    }

    /**
     * Retorna a caixa análise da Incorporação de processos
     * 
     * @param Spu_Entity_Processo $processo
     * @param integer $offset
     * @param integer $pageSize
     * @param string $filter
     * @return Spu_Entity_Processo[]
     */
    public function getCaixaAnaliseIncorporacao($processo, $offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/incorporacaocaixaanalise"
            . "/{$processo->id}/{$processo->assunto->id}/" . str_replace('/', '_', $processo->manifestante->cpf) . "/$offset/$pageSize/"
            . str_replace(array('/', ' '), array('_', ''), $filter);

        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }

    /**
     * Método auxiliar para retornar os processos à partir de uma URL
     * 
     * Existem vários serviços que retornam JSONs de Processos no SPU, e esses JSONs estão no mesmo formato. 
     * Este método existe para encapsular a lógica de captura desses JSONs.
     * 
     * @param string $url
     * @return array
     */
    protected function _getProcessosFromUrl($url)
    {
        $result = $this->_getResultFromUrl($url);

        return $result['Processos'][0];
    }

    /**
     * Faz uma requisição GET e retorna o seu resultado
     * 
     * @param string $url
     * @return array
     */
    protected function _getResultFromUrl($url)
    {
        return $this->_doAuthenticatedGetRequest($url);
    }

    public function hasFormulario($assuntoId)
    {
        $assuntoService = new Spu_Service_Assunto($this->getTicket());
        
        return $assuntoService->getAssunto($assuntoId)->formulario->data != null;
    }


    
    /**
     * Abre um processo no SPU
     * 
     * @param array $postData array com os parâmetros do serviço (podem ser verificados no webscript)
     * @return Spu_Entity_Processo o processo recém-aberto
     */
    public function abrirProcesso($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/abrir";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        $processo = $this->loadFromHash(array_pop(array_pop($result['Processo'][0])));

        return $this->_getProcessoDetalhado($processo);
    }

    /**
     * Retorna o processo com todas as informações possíveis
     * 
     * Popula anexos, assunto, tipo de processo e movimentações
     * 
     * @param Spu_Entity_Processo $processo
     * @return Spu_Entity_Processo
     */
    protected function _getProcessoDetalhado($processo)
    {
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

    /**
     * Retorna um processo através de seu ID
     * 
     * @param string $nodeUuid
     * @return Spu_Entity_Processo
     */
    public function getProcesso($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/get/$nodeUuid";
        
//        $name = $this->getNameForMethod('getProcesso', $nodeUuid);
//        if (($result = $this->getCache()->load($name)) === false) {
//
//            $result = $this->_doAuthenticatedGetRequest($url);
//
//            $this->getCache()->save($result, $name);
//        }
        
        $result = $this->_doAuthenticatedGetRequest($url);

        $processoHash = array_pop(array_pop($result['Processo'][0]));

        return $this->_getProcessoDetalhado($this->loadFromHash($processoHash));
    }

    /**
     * Retorna o histórico de movimentações de um processo através de seu ID
     * 
     * @param string $nodeUuid
     * @return Spu_Entity_Aspect_Movimentacao[]
     */
    public function getHistorico($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/historico/get/$nodeUuid";
        $result = $this->_doAuthenticatedGetRequest($url);

        $hashProcesso = array_pop($result['Processo']);
        $hashMovimentacao = $this->_getHashValue($hashProcesso, 'movimentacoes');

        return $this->_loadMovimentacoesFromHash($hashMovimentacao);
    }

    /**
     * Retorna array de Spu_Entity_Processo, que são os processos incorporados ao
     * processo cujo $uuid foi dado
     */
    public function getIncorporados($uuid)
    {
        $url = $this->getBaseUrl() . '/' . $this->_processoBaseUrl . '/' . $uuid . '/incorporados';
        $result = $this->_doAuthenticatedGetRequest($url);

        return $this->_loadManyFromHash($result['Processos'][0]);
    }

    /**
     * Incorpora um processo à outro
     * 
     * @param array $data postData com os parametros necessarios (podem ser conferidos no webscript)
     * @return array
     */
    public function incorporar($data)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/incorporar";
        $result = $this->_doAuthenticatedPostRequest($url, $data);

        return $result;
    }

    /**
     * Retorna todos os processos que atendem os critérios de pesquisa
     * 
     * @param array $postData array com os parametros de pesquisa (podem ser conferidos no webscript)
     * @return Spu_Entity_Processo[]
     */
    public function consultar($postData, $offset = 0, $pageSize = 20)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/consultar";
        
        $postData['offset'] = $offset;
        $postData['pageSize'] = $pageSize;
        
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $this->_loadManyFromHash($result['Processos'][0]);
    }
    
    public function buscar($postData, $offset = 0, $pageSize = 20)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/buscar";
        
        $postData['offset'] = $offset;
        $postData['pageSize'] = $pageSize;
        
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $this->_loadManyFromHash($result['Processos'][0]);
    }

    /**
     * Retorna todos os anexos de processos que possuem o filtro em seu conteúdo
     * 
     * @param integer $offset
     * @param integer $pageSize
     * @param string $filter conteudo a se buscar
     */
    public function consultarAnexos($offset = 0, $pageSize = 20, $filter)
    {
        $filter = urlencode($filter);
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/consultar-conteudo/$offset/$pageSize/$filter";
        $result = $this->_doAuthenticatedGetRequest($url);

        return $this->_loadManyAnexosFromHash($result['Anexos'][0]);
    }

    /**
     * Carrega vários processos através de um hash
     * 
     * @param array $hashAnexos
     * @return Spu_Entity_Anexo[]
     */
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

    /**
     * Carrega um anexo através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Anexo
     */
    protected function _loadAnexoFromHash($hash)
    {
        $anexo = new Spu_Entity_Anexo();
        $anexo->setId($this->_getHashValue($hash, 'noderef'));
        $anexo->setNome($this->_getHashValue($hash, 'nome'));
        $anexo->setMimetype($this->_getHashValue($hash, 'mimetype'));
        $anexo->setProcesso($this->loadFromHash(array_pop(array_pop($this->_getHashValue($hash, 'processo')))));

        return $anexo;
    }

    /**
     * Retorna todos os processos paralelos à um determinado processo
     * 
     * @param string $processoId
     * @return Spu_Entity_Processo[]
     */
    public function getProcessosParalelos($processoId)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/paralelos/$processoId";

        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }

    /**
     * Carrega um processo através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Processo
     */
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
        $processo->setTipoManifestante(
            $this->_loadTipoManifestanteFromHash($this->_getHashValue($hash, 'tipoManifestante'))
        );
        
        $processo->setArquivamento($this->_loadArquivamentoFromHash($this->_getHashValue($hash, 'arquivamento')));
        $processo->setMovimentacoes(
            $this->_loadMovimentacoesFromHash($this->_getHashValue($hash, 'ultimaMovimentacao'))
        );
        if (!empty($hash['folhas'])) {
            $processo->setFolhas($this->_loadFolhasFromHash($hash['folhas']));
        }

        return $processo;
    }

    /**
     * Carrega as folhas do processo através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Folhas
     */
    protected function _loadFolhasFromHash($hash)
    {
        $folhas = new Spu_Entity_Folhas();

        $folhas->setQuantidade($hash['quantidade']);

        if (!empty($hash['volumes'])) {
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
        }

        return $folhas;
    }

    /**
     * Carrega a prioridade do processo através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_Prioridade
     */
    protected function _loadPrioridadeFromHash($hash)
    {
        $prioridadeService = new Spu_Service_Prioridade($this->getTicket());
        $prioridade = $prioridadeService->loadFromHash($hash);

        return $prioridade;
    }

    /**
     * Carrega o status do processo através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_Status
     */
    protected function _loadStatusFromHash($hash)
    {
        $statusService = new Spu_Service_Status($this->getTicket());
        $status = $statusService->loadFromHash($hash);

        return $status;
    }

    /**
     * Carrega o protocolo atual do processo através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Protocolo
     */
    protected function _loadProtocoloFromHash($hash)
    {
        $protocoloService = new Spu_Service_Protocolo($this->getTicket());
        $protocolo = $protocoloService->loadFromHash($hash);

        return $protocolo;
    }

    /**
     * Carrega o tipo do processo através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_TipoProcesso
     */
    protected function _loadTipoProcessoFromHash($hash)
    {
        $tipoProcesso = new Spu_Entity_TipoProcesso($this->getTicket());
        $tipoProcesso->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $tipoProcesso->setNome($this->_getHashValue($hash, 'nome'));

        return $tipoProcesso;
    }

    /**
     * Carrega o protocolo proprietário do processo através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Protocolo
     */
    protected function _loadProprietarioFromHash($hash)
    {
        return $this->_loadProtocoloFromHash($hash);
    }

    /**
     * Carrega o assunto do processo através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Assunto
     */
    protected function _loadAssuntoFromHash($hash)
    {
        $assuntoService = new Spu_Service_Assunto($this->getTicket());
        $assunto = $assuntoService->loadFromHash($hash);
        
        return $assunto;
    }

    /**
     * Carrega o manifestatne do processo através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Aspect_Manifestante
     */
    protected function _loadManifestanteFromHash($hash)
    {
        $manifestanteService = new Spu_Service_Manifestante($this->getTicket());
        $manifestante = $manifestanteService->loadFromHash($hash);

        return $manifestante;
    }

    /**
     * Carrega o tipo de manifestante do processo através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_TipoManifestante
     */
    protected function _loadTipoManifestanteFromHash($hash)
    {
        $tipoManifestanteService = new Spu_Service_TipoManifestante($this->getTicket());
        $tipoManifestante = $tipoManifestanteService->loadFromHash($hash);

        return $tipoManifestante;
    }

    /**
     * Carrega o arquivamento de um processo através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Aspect_Arquivamento
     */
    protected function _loadArquivamentoFromHash($hash)
    {
        $arquivamentoService = new Spu_Service_Arquivamento();
        $arquivamento = $arquivamentoService->loadFromHash($hash);

        return $arquivamento;
    }

    /**
     * Carrega as movimentações de um processo através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Aspect_Movimentacao[]
     */
    protected function _loadMovimentacoesFromHash($hash)
    {
        $movimentacaoService = new Spu_Service_Movimentacao();

        return $movimentacaoService->loadManyFromHash($hash);
    }

    /**
     * Carrega vários processos através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Processo[]
     */
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