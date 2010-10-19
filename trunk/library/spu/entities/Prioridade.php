<?php
require_once('../library/Alfresco/API/AlfrescoTiposProcesso.php');
require_once('BaseAlfrescoEntity.php');
class Prioridade extends BaseAlfrescoEntity
{
    protected $_nodeRef;
    protected $_nome;
    protected $_descricao;
    
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
    
    public function getDescricao()
    {
        return $this->_descricao;
    }
    
    public function setDescricao($value)
    {
        $this->_descricao = $value;
    }
    
    public function getId()
    {
        $nodeRef = $this->getNodeRef();
        return substr($nodeRef, strrpos($nodeRef, '/') + 1);
    }
    
	public function listar()
    {
        $service = new AlfrescoProcesso(self::ALFRESCO_URL, $this->_getTicket());
        $hashDePrioridade = $service->getPrioridades();
        
        $prioridades = array();
        foreach ($hashDePrioridade as $hashPrioridade) {
            $prioridade = new Prioridade($this->_getTicket());
            $prioridade->_loadPrioridadeFromHash($hashPrioridade[0]);
            $prioridades[] = $prioridade;
        }
        
        return $prioridades;
    }
    
    protected function _loadPrioridadeFromHash($hash)
    {
        $this->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $this->setNome($this->_getHashValue($hash, 'nome'));
        $this->setDescricao($this->_getHashValue($hash, 'descricao'));
    }
}
?>