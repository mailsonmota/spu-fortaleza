<?php
require_once('../library/Alfresco/API/AlfrescoBairros.php');
require_once('BaseAlfrescoEntity.php');
class Bairro extends BaseAlfrescoEntity
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
        $service = new AlfrescoBairros(self::ALFRESCO_URL, $this->_getTicket());
        $hashDeBairros = $service->getBairros();
        
        $bairros = array();
        foreach ($hashDeBairros[0] as $hashBairro) {
            $bairro = new Bairro($this->_getTicket());
            $bairro->_loadBairroFromHash($hashBairro[0]);
            $bairros[] = $bairro;
        }
        
        return $bairros;
    }
    
    protected function _loadBairroFromHash($hash)
    {
        $this->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $this->setNome($this->_getHashValue($hash, 'nome'));
        $this->setDescricao($this->_getHashValue($hash, 'descricao'));
    }
}
?>