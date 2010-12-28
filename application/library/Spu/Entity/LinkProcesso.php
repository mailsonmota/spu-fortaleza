<?php
require_once('BaseEntity.php');
require_once('Processo.php');
abstract class LinkProcesso extends BaseEntity
{
    protected $_nodeRef;
    protected $_nome;
    protected $_processo;
    
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
    
    public function getProcesso() {
        return $this->_processo;
    }
    
    public function setProcesso($value)
    {
        $this->_processo = $value;
    }
    
    public function getId()
    {
        $nodeRef = $this->getNodeRef();
        return substr($nodeRef, strrpos($nodeRef, '/') + 1);
    }
    
    public function getNumeroProcesso()
    {
        return $this->getProcesso()->getNumero();
    }
    
    public function getDataProcesso()
    {
        return $this->getProcesso()->getData();
    }
    
    public function getNomeManifestanteProcesso() {
        return $this->getProcesso()->getManifestante()->getNome();
    }
    
    public function getNomeTipoProcesso() {
        return $this->getProcesso()->getNomeTipoProcesso();
    }
    
    public function getNomeAssuntoProcesso() {
        return $this->getProcesso()->getNomeAssunto();
    }
    
    public function getIdProcesso() {
        return $this->getProcesso()->getId();
    }
    
    public function loadFromHash($hash)
    {
        $this->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $this->setNome($this->_getHashValue($hash, 'nome'));
        $this->setProcesso($this->_loadProcessoFromHash($this->_getHashValue($hash, 'Processo')));
    }
    
    public function _loadProcessoFromHash($hash)
    {
        $hashProcesso = array_pop($hash);
        $hashProcesso = array_pop($hashProcesso);
        $hashDadosProcesso = array_pop($hashProcesso);
        $processo = new Processo();
        $processo->loadFromHash($hashDadosProcesso);
        return $processo;
    }
}