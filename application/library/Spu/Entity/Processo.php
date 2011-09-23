<?php
/**
 * Representa um processo do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Entity_Abstract
 */
class Spu_Entity_Processo extends Spu_Entity_Abstract
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
    protected $_folhas;
    
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
    
    /**
     * @return Spu_Entity_Aspect_Manifestante
     */
    public function getManifestante()
    {
        return $this->_manifestante;
    }
    
    public function setManifestante($value)
    {
        $this->_manifestante = $value;
    }
    
    /**
     * @return Spu_Entity_Classification_TipoManifestante
     */
    public function getTipoManifestante()
    {
        return $this->_tipoManifestante;
    }
    
    public function setTipoManifestante($value)
    {
        $this->_tipoManifestante = $value;
    }
    
    /**
     * @return Spu_Entity_Classification_Prioridade
     */
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
    
    /**
     * Local atual do protocolo
     * 
     * @return Spu_Entity_Protocolo
     */
    public function getProtocolo()
    {
        return $this->_protocolo;
    }
    
    public function setProtocolo($value)
    {
        $this->_protocolo = $value;
    }
    
    /**
     * @return Spu_Entity_TipoProcesso
     */
    public function getTipoProcesso()
    {
        return $this->_tipoProcesso;
    }
    
    public function setTipoProcesso($value)
    {
        $this->_tipoProcesso = $value;
    }
    
    /**
     * Retorna o protocolo proprietário do processo, ou seja, o protocolo de origem.
     *  
     * @return Spu_Entity_Protocolo
     */
    public function getProprietario()
    {
        return $this->_proprietario;
    }
    
    public function setProprietario($value)
    {
        $this->_proprietario = $value;
    }
    
    /**
     * @return Spu_Entity_Assunto
     */
    public function getAssunto()
    {
        return $this->_assunto;
    }
    
    public function setAssunto($value)
    {
        $this->_assunto = $value;
    }
    
    /**
     * Retorna o histórico de movimentações do processo
     * 
     * @return Spu_Entity_Aspect_Movimentacao[]
     */
    public function getMovimentacoes()
    {
        return $this->_movimentacoes;
    }
    
    public function setMovimentacoes($value)
    {
        $this->_movimentacoes = $value;
    }
    
    /**
     * @return Spu_Entity_Classification_Status
     */
    public function getStatus()
    {
        return $this->_status;
    }
    
    public function setStatus($value)
    {
        $this->_status = $value;
    }
    
    /**
     * Retorna os anexos do processo
     * 
     * @return Spu_Entity_Anexo[]
     */
    public function getArquivos()
    {
        return $this->_arquivos;
    }
    
    public function setArquivos($value)
    {
        $this->_arquivos = $value;
    }
    
    /**
     * @return Spu_Aspect_Arquivamento
     */
    public function getArquivamento() {
        return $this->_arquivamento;
    }
    
    public function setArquivamento($value)
    {
        $this->_arquivamento = $value;
    }
    
    /**
     * Retorna as respostas do formulário do assunto do processo
     * 
     * @return Spu_Entity_RespostasFormulario
     */
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
        return str_ireplace('_', '/', $this->getNome());
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
    
    /**
     * @return Spu_Entity_Folhas
     */
    public function getFolhas()
    {
        return $this->_folhas;
    }
    
    public function setFolhas($data)
    {
        $this->_folhas = $data;
    }
    
    /**
     * @return boolean
     */
    public function isArquivado()
    {
        return ($this->getStatus()->nome == Spu_Entity_Classification_Status::ARQUIVADO);
    }
    
    /**
     * @return boolean
     */
    public function hasArquivos()
    {
        if (count($this->getArquivos())) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @return boolean
     */
    public function hasRespostasFormulario()
    {
        return $this->_respostasFormulario->hasData();
    }
}