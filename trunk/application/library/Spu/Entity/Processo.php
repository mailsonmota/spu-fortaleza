<?php
require_once('BaseEntity.php');
require_once('TipoProcesso.php');
require_once('Protocolo.php');
require_once('Arquivo.php');
require_once('RespostasFormulario.php');
Loader::loadAspect('Movimentacao');
Loader::loadAspect('Manifestante');
Loader::loadAspect('Arquivamento');
Loader::loadClassification('Prioridade');
Loader::loadClassification('Status');
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
    protected $_respostasFormulario;
    
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
    
    public function getRespostasFormulario()
    {
        return $this->_respostasFormulario;
    }
    
    public function setRespostasFormulario($value)
    {
        $this->_respostasFormulario = $value;
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
        return $this->numero . ' - ' . $this->getProprietario()->path . ' (' . $this->getTipoProcesso()->nome . ')';
    }
    
    public function getNomeProtocolo()
    {
        return $this->getProtocolo()->getPath();
    }
    
    public function isArquivado()
    {
        return ($this->getStatus()->nome == Status::ARQUIVADO);
    }
    
    public function hasArquivos()
    {
        if (count($this->getArquivos())) {
            return true;
        }
    }
    
    public function hasRespostasFormulario()
    {
        return $this->_respostasFormulario->hasData();
    }
}