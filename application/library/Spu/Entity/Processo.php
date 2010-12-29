<?php
require_once('BaseEntity.php');
require_once('TipoProcesso.php');
require_once('Protocolo.php');
require_once('Arquivo.php');
Loader::loadAspect('Movimentacao');
Loader::loadAspect('Manifestante');
Loader::loadAspect('Arquivamento');
Loader::loadClassification('Prioridade');
Loader::loadClassification('Status');
Loader::loadDao('ProcessoDao');
class Processo extends BaseEntity
{
    protected $_nodeRef;
    protected $_nome;
    protected $_data;
    protected $_manifestante;
    protected $_tipoManifestante;
    protected $_prioridade;
    protected $_numeroOrigem;
    protected $_observacao;
    protected $_corpo;
    protected $_dataPrazo;
    protected $_protocolo;
    protected $_proprietario;
    protected $_tipoProcesso;
    protected $_assunto;
    protected $_movimentacoes;
    protected $_status;
    protected $_arquivamento;
    protected $_arquivos;
    
    public function getNodeRef()
    {
        return $this->_nodeRef;
    }
    
    public function setNodeRef($nodeRef)
    {
        $this->_nodeRef = $nodeRef;
    }
    
    public function getNome()
    {
        return $this->_nome;
    }
    
    public function setNome($nome)
    {
        $this->_nome = $nome;
    }
    
    public function getData()
    {
        return $this->_data;
    }
    
    public function setData($data)
    {
        $this->_data = $data;
    }
    
    public function getManifestante()
    {
        return $this->_manifestante;
    }
    
    public function setManifestante($value)
    {
        $this->_manifestante = $value;
    }
    
    public function getTipoManifestante()
    {
        return $this->_tipoManifestante;
    }
    
    public function setTipoManifestante($value)
    {
        $this->_tipoManifestante = $value;
    }
    
    public function getPrioridade()
    {
        return $this->_prioridade;
    }
    
    public function setPrioridade($value)
    {
        $this->_prioridade = $value;
    }
    
    public function getNumeroOrigem()
    {
        return $this->_numeroOrigem;
    }
    
    public function setNumeroOrigem($value)
    {
        $this->_numeroOrigem = $value;
    }
    
    public function getObservacao()
    {
        return $this->_observacao;
    }
    
    public function setObservacao($value)
    {
        $this->_observacao = $value;
    }
    
    public function getCorpo()
    {
        return $this->_corpo;
    }
    
    public function setCorpo($value)
    {
        $this->_corpo = $value;
    }
    
    public function getDataPrazo()
    {
        return $this->_dataPrazo;
    }
    
    public function setDataPrazo($value)
    {
        $this->_dataPrazo = $value;
    }
    
    public function getProtocolo()
    {
        return $this->_protocolo;
    }
    
    public function setProtocolo($value)
    {
        $this->_protocolo = $value;
    }
    
    public function getTipoProcesso()
    {
        return $this->_tipoProcesso;
    }
    
    public function setTipoProcesso($value)
    {
        $this->_tipoProcesso = $value;
    }
    
    public function getProprietario()
    {
        return $this->_proprietario;
    }
    
    public function setProprietario($value)
    {
        $this->_proprietario = $value;
    }
    
    public function getAssunto()
    {
        return $this->_assunto;
    }
    
    public function setAssunto($value)
    {
        $this->_assunto = $value;
    }
    
    public function getMovimentacoes()
    {
        return $this->_movimentacoes;
    }
    
    public function setMovimentacoes($value)
    {
        $this->_movimentacoes = $value;
    }
    
    public function getStatus()
    {
        return $this->_status;
    }
    
    public function setStatus($value)
    {
        $this->_status = $value;
    }
    
    public function getArquivos()
    {
        return $this->_arquivos;
    }
    
    public function setArquivos($value)
    {
        $this->_arquivos = $value;
    }
    
    /**
     * @return Arquivamento
     */
    public function getArquivamento() {
        return $this->_arquivamento;
    }
    
    public function setArquivamento($value)
    {
        $this->_arquivamento = $value;
    }
    
    public function getId()
    {
        $nodeRef = $this->getNodeRef();
        return substr($nodeRef, strrpos($nodeRef, '/') + 1);
    }
    
    public function getNumero()
    {
        $nome = $this->getNome();
        return str_ireplace('_', '/', $nome);
    }
    
    public function getNomeTipoProcesso()
    {
        return $this->getTipoProcesso()->nome;
    }
    
    public function getNomeAssunto()
    {
        return $this->getAssunto()->nome;
    }
    
    public function getNomeManifestante()
    {
        return $this->getManifestante()->nome;
    }
    
    public function getNomeDescritivo()
    {
        return $this->numero . ' - ' . $this->getProprietario()->nome . ' (' . $this->getTipoProcesso()->nome . ')';
    }
    
    public function getNomeProtocolo()
    {
        return $this->getProtocolo()->getNome();
    }
    
    public function isArquivado()
    {
        return ($this->getStatus()->nome == Status::ARQUIVADO);
    }
    
    public function listarProcessosCaixaEntrada()
    {
        $dao = $this->_getDao();
        $hashProcessos = $dao->getCaixaEntrada();
        
        return $this->_loadManyFromHash($hashProcessos);
    }
    
    protected function _getDao()
    {
        $dao = new ProcessoDao($this->_getTicket());
        return $dao;
    }
    
    public function getTotalProcessosCaixaEntrada()
    {
        $processosCaixaEntrada = $this->listarProcessosCaixaEntrada();
        return count($processosCaixaEntrada);
    }
    
    public function listarProcessosCaixaSaida()
    {
        $dao = $this->_getDao();
        $hashProcessos = $dao->getCaixaSaida();
        
        return $this->_loadManyFromHash($hashProcessos);
    }
    
    public function listarProcessosCaixaAnalise()
    {
        $dao = $this->_getDao();
        $hashProcessos = $dao->getCaixaAnalise();
        
        return $this->_loadManyFromHash($hashProcessos);
    }
    
    public function listarProcessosCaixaEnviados()
    {
        $dao = $this->_getDao();
        $hashProcessos = $dao->getCaixaEnviados();
        
        return $this->_loadManyFromHash($hashProcessos);
    }
    
    public function listarProcessosCaixaExternos()
    {
        $dao = $this->_getDao();
        $hashProcessos = $dao->getCaixaExternos();
        
        return $this->_loadManyFromHash($hashProcessos);
    }
    
    public function listarProcessosArquivados()
    {
        $dao = $this->_getDao();
        $hashProcessos = $dao->getCaixaArquivo();
        
        return $this->_loadManyFromHash($hashProcessos);
    }
    
    protected function _loadManyFromHash($hashProcessos)
    {
        $processos = array();
        foreach ($hashProcessos as $hashProcesso) {
            $hashDadosProcesso = array_pop($hashProcesso); 
            $processo = new Processo($this->_getTicket());
            $processo->loadFromHash($hashDadosProcesso);
            $processos[] = $processo;
        }
        
        return $processos;
    }
    
    public function loadFromHash($hash)
    {
        $this->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $this->setNome($this->_getHashValue($hash, 'nome'));
        $this->setCorpo($this->_getHashValue($hash, 'corpo'));
        $this->setData($this->_getHashValue($hash, 'data'));
        $this->setPrioridade($this->_loadPrioridadeFromHash($this->_getHashValue($hash, 'prioridade')));
        $this->setStatus($this->_loadStatusFromHash($this->_getHashValue($hash, 'status')));
        $this->setObservacao($this->_getHashValue($hash, 'observacao'));
        $this->setNumeroOrigem($this->_getHashValue($hash, 'numeroOrigem'));
        $this->setProtocolo($this->_loadProtocoloFromHash($this->_getHashValue($hash, 'localAtual')));
        $this->setTipoProcesso($this->_loadTipoProcessoFromHash($this->_getHashValue($hash, 'tipoProcesso')));
        $this->setProprietario($this->_loadProprietarioFromHash($this->_getHashValue($hash, 'proprietario')));
        $this->setAssunto($this->_loadAssuntoFromHash($this->_getHashValue($hash, 'assunto')));
        $this->setManifestante($this->_loadManifestanteFromHash($this->_getHashValue($hash, 'manifestante')));
        $this->setTipoManifestante($this->_loadTipoManifestanteFromHash($this->_getHashValue($hash,
                                                                                             'tipoManifestante')));
        $this->setArquivamento($this->_loadArquivamentoFromHash($this->_getHashValue($hash, 'arquivamento')));
    }
    
    protected function _loadPrioridadeFromHash($hash)
    {
        $hash = array_pop($hash);
        $prioridade = new Prioridade($this->_ticket);
        $prioridade->loadFromHash($hash);
        
        return $prioridade;
    }
    
    protected function _loadStatusFromHash($hash)
    {
        $hash = array_pop($hash);
        $status = new Status($this->_ticket);
        $status->loadFromHash($hash);
        
        return $status;
    }
    
    protected function _loadProtocoloFromHash($hash)
    {
        $hash = array_pop($hash);
        $protocolo = new Protocolo($this->_ticket);
        $protocolo->loadFromHash($hash);
        
        return $protocolo;
    }
    
    protected function _loadTipoProcessoFromHash($hash)
    {
        $hash = array_pop($hash);
        $tipoProcesso = new TipoProcesso($this->_ticket);
        $tipoProcesso->setNodeRef($hash['noderef']);
        $tipoProcesso->setNome($hash['nome']);
        
        return $tipoProcesso;
    }
    
    protected function _loadProprietarioFromHash($hash)
    {
        return $this->_loadProtocoloFromHash($hash);
    }
    
    protected function _loadAssuntoFromHash($hash)
    {
        $hash = array_pop($hash);
        $assunto = new Assunto($this->_ticket);
        $assunto->setNodeRef($hash['noderef']);
        $assunto->setNome($hash['nome']);
        
        return $assunto;
    }
    
    protected function _loadManifestanteFromHash($hash)
    {
        $hash = array_pop($hash);
        $manifestante = new Manifestante($this->_ticket);
        $manifestante->loadFromHash($hash);
        
        return $manifestante;
    }
    
    protected function _loadTipoManifestanteFromHash($hash)
    {
        $hash = array_pop($hash);
        $tipoManifestante = new TipoManifestante($this->_ticket);
        $tipoManifestante->loadFromHash($hash);
        
        return $tipoManifestante;
    }
    
    protected function _loadArquivamentoFromHash($hash)
    {
        $hash = array_pop($hash);
        $arquivamento = new Arquivamento();
        $arquivamento->loadFromHash($hash);
        
        return $arquivamento;
    }
    
    public function carregarPeloId($id)
    {
        $dao = $this->_getDao();
        $hashProcessos = $dao->getProcesso($id);
        
        $processo = array();
        foreach ($hashProcessos as $hashProcesso) {
            $hashDadosProcesso = array_pop($hashProcesso);
            $this->loadFromHash($hashDadosProcesso);
        }
        
        // TODO Colocar funcionalidade para dentro dos serviços
        $this->carregarArquivos();
        
        return $processo;
    }
    
    public function abrirProcesso($postData)
    {
        $dao = $this->_getDao();
        
        try {
            $abrirProcessoRetorno = $dao->abrirProcesso($postData);
        } catch (Exception $e) {
            throw new Exception('Houve um erro na abertura do processo', $e->getMessage());
        }
        
        $hashProcesso = $abrirProcessoRetorno["Processo"][0];
        foreach ($hashProcesso as $hash) {
            $this->loadFromHash($hash[0]);
        }

        return $this;
    }
    
    public function tramitar($postData)
    {
        $dao = $this->_getDao();
        try {
           $return = $dao->tramitar($postData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            //throw new AlfrescoApiException('Houve um erro na tramitação do processo', $e->getMessage());
        }
        
        return $return;
    }
    
    public function tramitarVarios($postData)
    {
        $dao = $this->_getDao();
        try {
           $return = $dao->tramitarVarios($postData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            //throw new AlfrescoApiException('Houve um erro na tramitação dos processos');
        }
        
        return $return;
    }
    
    public function receberVarios($postData)
    {
        $dao = $this->_getDao();
        return $dao->receberVarios($postData);
    }
    
    public function tramitarExternos($postData)
    {
        $dao = $this->_getDao();
        try {
           $return = $dao->tramitarExternos($postData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            //throw new AlfrescoApiException('Houve um erro na tramitação do processo', $e->getMessage());
        }
        
        return $return;
    }
    
    public function retornarExternos($postData)
    {
        $dao = $this->_getDao();
        try {
           $return = $dao->retornarExternos($postData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            //throw new AlfrescoApiException('Houve um erro na tramitação do processo', $e->getMessage());
        }
        
        return $return;
    }
    
    public function carregarMovimentacoes()
    {
        $dao = $this->_getDao();
        $hashProcessos = $dao->getHistorico($this->id);
        
        $processo = array();
        foreach ($hashProcessos as $hashProcesso) {
            $hashDadosProcesso = array_pop($hashProcesso);
            $hashMovimentacao = $this->_getHashValue($hashDadosProcesso, 'movimentacoes');
            $this->setMovimentacoes($this->_loadMovimentacoesFromHash($hashMovimentacao));
        }
        
        return $processo;
    }
    
    protected function _loadMovimentacoesFromHash($hash)
    {
        $movimentacoes = array();
        if ($hash) {
            foreach ($hash[0] as $hashMovimentacao) {
                $hashMovimentacao = array_pop($hashMovimentacao);
                $movimentacao = new Movimentacao();
                $movimentacao->loadFromHash($hashMovimentacao);
                
                $movimentacoes[] = $movimentacao;
            }
        }
        
        return $movimentacoes;
    }
    
    public function cancelarEnvios($postData)
    {
        $dao = $this->_getDao();
        try {
           $return = $dao->cancelarEnvios($postData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            //throw new AlfrescoApiException('Houve um erro na tramitação do processo', $e->getMessage());
        }
        
        return $return;
    }
    
    public function arquivarVarios($postData)
    {
        $dao = $this->_getDao();
        try {
           $return = $dao->arquivarVarios($postData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            //throw new AlfrescoApiException('Houve um erro na tramitação do processo', $e->getMessage());
        }
        
        return $return;
    }
    
    public function reabrirVarios($postData)
    {
        $dao = $this->_getDao();
        try {
           $return = $dao->reabrirVarios($postData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            //throw new AlfrescoApiException('Houve um erro na tramitação do processo', $e->getMessage());
        }
        
        return $return;
    }
    
    public function uploadArquivo($data)
    {
        $dao = $this->_getDao();
        try {
            $return = $dao->uploadArquivo($data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        
        return $return;
    }
    
    public function incorporar($data)
    {
        $dao = $this->_getDao();
        try {
            $return = $dao->incorporar($data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        
        return $return;
    }
    
    public function comentarVarios($postData)
    {
        $dao = $this->_getDao();
        try {
           $return = $dao->comentarVarios($postData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            //throw new AlfrescoApiException('Houve um erro na tramitação do processo', $e->getMessage());
        }
        
        return $return;
    }
    
    public function consultar($postData)
    {
        $dao = $this->_getDao();
        $processos = array();
        
        try {
           $hashProcessos = $dao->consultar($postData);
           $processos = $this->_loadManyFromHash($hashProcessos);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            //throw new AlfrescoApiException('Houve um erro na tramitação do processo', $e->getMessage());
        }
        
        return $processos;
    }
    
    /*
     * Lista os processos possíveis de ser incorporados a um outro ($this).
     * 
     * Critérios: as características tipo de processo, assunto e cpf do manifestante do processo incorporado
     * devem ser iguais as do principal. Também, o próprio processo principal ($this) é excluído da lista. 
     */
    public function listarProcessosCaixaAnaliseIncorporado()
    {
        $listaCaixaAnalise = $this->listarProcessosCaixaAnalise();
        $listaCaixaAnaliseFiltrada = Array();
        
        for ($i = 0; $i < count($listaCaixaAnalise); $i++) {
            if (
                ($listaCaixaAnalise[$i]->tipoProcesso->nodeRef == $this->tipoProcesso->nodeRef) AND
                ($listaCaixaAnalise[$i]->assunto->nodeRef == $this->assunto->nodeRef) AND
                ($listaCaixaAnalise[$i]->manifestante->cpf == $this->manifestante->cpf) AND
                ($listaCaixaAnalise[$i]->nome != str_replace("/", "_", $this->numero))
               ) {
                $listaCaixaAnaliseFiltrada[] = $listaCaixaAnalise[$i];
            }
        }

        return $listaCaixaAnaliseFiltrada;
    }
    
    public function carregarArquivos()
    {
        $dao = $this->_getDao();
        $arquivos = $dao->getArquivos($this->id);
        
        $arquivosReturn = Array();
        foreach ($arquivos as $arquivo) {
             $arquivoTmp = new Arquivo();
             $arquivoTmp->setNome($arquivo['nome']);
             $arquivoTmp->setDownloadUrl($dao->getBaseUrl() . $arquivo['download']);
             $arquivosReturn[] = $arquivoTmp;
        }
        $this->setArquivos($arquivosReturn);
        
        return $arquivosReturn;
    }
    
    public function hasArquivos()
    {
        if (count($this->getArquivos())) {
            return true;
        }
    }
}