<?php
/**
 * Representa um modelo de Link de Processo do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Entity_Abstract
 */
abstract class Spu_Entity_LinkProcesso extends Spu_Entity_Abstract
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
    
    /**
     * @return Spu_Entity_Processo
     */
    public function getProcesso()
    {
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
    
    public function getNomeManifestanteProcesso()
    {
        return $this->getProcesso()->getManifestante()->getNome();
    }
    
    public function getNomeTipoProcesso()
    {
        return $this->getProcesso()->getNomeTipoProcesso();
    }
    
    public function getNomeAssuntoProcesso()
    {
        return $this->getProcesso()->getNomeAssunto();
    }
    
    public function getIdProcesso()
    {
        return $this->getProcesso()->getId();
    }
}