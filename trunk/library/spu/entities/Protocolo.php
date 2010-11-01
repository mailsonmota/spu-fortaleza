<?php
require_once('../library/Alfresco/API/AlfrescoProtocolo.php');
require_once('BaseAlfrescoEntity.php');
class Protocolo extends BaseAlfrescoEntity
{
    protected $_nodeRef;
    protected $_nome;
    protected $_descricao;
    protected $_orgao;
    protected $_lotacao;
    
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
    
    public function setDescricao($data)
    {
        $this->_descricao = $data;
    }
    
    public function getOrgao()
    {
        return $this->_orgao;
    }
    
    public function setOrgao($data)
    {
        $this->_orgao = $data;
    }
    
    public function getLotacao()
    {
        return $this->_lotacao;
    }
    
    public function setLotacao($data)
    {
        $this->_lotacao = $data;
    }
    
    public function getId()
    {
        $nodeRef = $this->getNodeRef();
        return substr($nodeRef, strrpos($nodeRef, '/') + 1);
    }
    
    public function getOrgaoLotacao()
    {
    	if ($this->getLotacao()) {
    		$orgaoLotacao = $this->getLotacao();
    		if ($this->getOrgao()) {
    			$orgaoLotacao = $this->getOrgao() . " - " . $orgaoLotacao;
    		}
    	} else {
    		$orgaoLotacao = $this->getNome();
    	}
    	return $orgaoLotacao;
    }
    
    public function listar()
    {
        $service = new AlfrescoProtocolo(self::ALFRESCO_URL, $this->_getTicket());
        $hashProtocolos = $service->getProtocolos();
        
        $protocolos = array();
        foreach ($hashProtocolos as $hashProtocolo) {
            $hashProtocolo = array_pop($hashProtocolo); 
            $protocolo = new Protocolo($this->_getTicket());
            $protocolo->loadFromHash($hashProtocolo);
            $protocolos[] = $protocolo;
        }
        
        return $protocolos;
    }
    
    public function loadFromHash($hash)
    {
        $this->setNodeRef($hash['noderef']);
        $this->setNome($hash['nome']);
        $this->setDescricao($hash['descricao']);
    }
}
?>