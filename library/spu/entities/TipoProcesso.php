<?php
require_once('../library/Alfresco/API/AlfrescoTiposProcesso.php');
require_once('BaseAlfrescoEntity.php');
class TipoProcesso extends BaseAlfrescoEntity
{
    protected $_nodeRef;
    protected $_nome;
    protected $_categoriaMaeDosAssuntos;
    protected $_simples;
    
    public function getId()
    {
        $nodeRef = $this->getNodeRef();
        return substr($nodeRef, strrpos($nodeRef, '/') + 1);
    }
    
    public function getNome()
    {
        return $this->_nome;
    }
    
    public function setNome($nome)
    {
        $this->_nome = $nome;
    }
    
    public function getNodeRef()
    {
        return $this->_nodeRef;
    }
    
    public function setNodeRef($nodeRef)
    {
        $this->_nodeRef = $nodeRef;
    }
    
    public function listar()
    {
        $service = new AlfrescoTiposProcesso(self::ALFRESCO_URL, $this->_getTicket());
        $hashDeTiposProcesso = $service->getTiposProcesso();
        
        $tiposProcesso = array();
        foreach ($hashDeTiposProcesso as $hashTipoProcesso) {
            $hashDadosTipoProcesso = array_pop(array_pop($hashTipoProcesso)); 
            $tipoProcesso = new TipoProcesso($this->_getTicket());
            $tipoProcesso->loadTipoProcessoFromHash($hashDadosTipoProcesso);
            $tiposProcesso[] = $tipoProcesso;        }
        
        return $tiposProcesso;
    }
    
    public function loadTipoProcessoFromHash($hash)
    {
        $this->setNodeRef($hash['noderef']);
        $this->setNome($hash['nome']);
    }
}
?>