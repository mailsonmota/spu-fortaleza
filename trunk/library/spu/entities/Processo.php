<?php
require_once('../library/Alfresco/API/AlfrescoProcesso.php');
require_once('BaseAlfrescoEntity.php');
require_once('TipoProcesso.php');
class Processo extends BaseAlfrescoEntity
{
    protected $_nodeRef;
    protected $_nome;
    protected $_data;
    protected $_envolvido;
    protected $_prioridade;
    protected $_numeroOrigem;
    protected $_tipoProcesso;
    protected $_assunto;
    
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
    
    public function getEnvolvido()
    {
        return $this->_envolvido;
    }
    
    public function setEnvolvido($value)
    {
        $this->_envolvido = $value;
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
    
    public function getTipoProcesso()
    {
        return $this->_tipoProcesso;
    }
    
    public function setTipoProcesso($value)
    {
        $this->_tipoProcesso = $value;
    }
    
    public function getAssunto()
    {
        return $this->_assunto;
    }
    
    public function setAssunto($value)
    {
        $this->_assunto = $value;
    }
    
    public function getId()
    {
        $nodeRef = $this->getNodeRef();
        return substr($nodeRef, strrpos($nodeRef, '/') + 1);
    }
    
    public function getNomeTipoProcesso()
    {
        return $this->getTipoProcesso()->nome;
    }
    
    public function getNomeAssunto()
    {
        return $this->getAssunto()->nome;
    }
    
    public function listarProcessosCaixaEntrada()
    {
        $service = new AlfrescoProcesso(self::ALFRESCO_URL, $this->_getTicket());
        $hashProcessos = $service->getCaixaEntrada();
        
        $processos = array();
        foreach ($hashProcessos as $hashProcesso) {
            $hashDadosProcesso = array_pop($hashProcesso); 
            $processo = new Processo($this->_getTicket());
            $processo->_loadProcessoFromHash($hashDadosProcesso);
            $processos[] = $processo;
        }
        
        return $processos;
    }
    
    protected function _loadProcessoFromHash($hash)
    {
        $this->setNodeRef($hash['noderef']);
        $this->setNome($hash['nome']);
        $this->setData($hash['data']);
        $this->setEnvolvido($hash['envolvido']);
        $this->setPrioridade($hash['prioridade']);
        $this->setNumeroOrigem($hash['numeroOrigem']);
        $this->setTipoProcesso($this->_loadTipoProcessoFromHash($hash['tipoProcesso'][0]));
        $this->setAssunto($this->_loadAssuntoFromHash($hash['assunto'][0]));
    }
    
    protected function _loadTipoProcessoFromHash($hash)
    {
        $tipoProcesso = new TipoProcesso($this->_ticket);
        $tipoProcesso->setNodeRef($hash['noderef']);
        $tipoProcesso->setNome($hash['nome']);
        
        return $tipoProcesso;
    }
    
    protected function _loadAssuntoFromHash($hash)
    {
        $assunto = new Assunto($this->_ticket);
        $assunto->setNodeRef($hash['noderef']);
        $assunto->setNome($hash['nome']);
        
        return $assunto;
    }
    
    public function carregarPeloId($id)
    {
        $service = new AlfrescoProcesso(self::ALFRESCO_URL, $this->_getTicket());
        $hashProcessos = $service->getTipoProcesso($id);
        
        $processo = array();
        foreach ($hashProcessos as $hashProcesso) {
            $hashDadosProcesso = array_pop($hashProcesso); 
            $this->loadProcessoFromHash($hashDadosProcesso);
        }
        
        return $processo;
    }
    
    public function abrirProcesso($postData)
    {
        $service = new AlfrescoProcesso(self::ALFRESCO_URL, $this->_getTicket());
        return $service->abrirProcesso($postData);
    }
}
?>