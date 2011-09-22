<?php
/**
 * Representa um protocolo do SPU
 * 
 * Um Protocolo é um setor que tramita processos, usualmente é uma lotação. Ex.: SAM, AMC, ETUFOR, etc...
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Entity_Abstract
 */
class Spu_Entity_Protocolo extends Spu_Entity_Abstract
{
    protected $_nodeRef;
    protected $_nome;
    protected $_descricao;
    protected $_orgao;
    protected $_lotacao;
    protected $_parent;
    protected $_path;
    
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

    /**
     * Retorna o caminho completo do protocolo
     * 
     * Exemplo: SAM/GABS/DAF/PROT
     * 
     * @return string
     */
    public function getPath()
    {
        return ($this->_path) ? $this->_path : $this->getNome();
    }

    public function setPath($value)
    {
        $this->_path = $value;
    }

    /**
     * @return Spu_Entity_Protocolo | null
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * @param Spu_Entity_Protocolo $value
     */
    public function setParent(Spu_Entity_Protocolo $value)
    {
        $this->_parent = $value;
    }

    /**
     * Retorna o nodeUuid do processo (é o final do nodeRef)
     * 
     * @return string
     */
    public function getId()
    {
        $nodeRef = $this->getNodeRef();
        return substr($nodeRef, strrpos($nodeRef, '/') + 1);
    }
    
    /**
     * Retorna o nome descritivo do protocolo, no formato Órgão - Lotação
     * 
     * @return string
     */
    public function getOrgaoLotacao()
    {
        if ($this->getLotacao()) {
            $orgaoLotacao = $this->getLotacao();
            if ($this->getOrgao()) {
                $orgaoLotacao = $this->getOrgao() . " - " . $orgaoLotacao;
            }
        } else {
            $orgaoLotacao = ($this->getDescricao()) ? $this->getDescricao() : $this->getNome();
        }
        return $orgaoLotacao;
    }
    
    /**
     * Retorna a sigla do Protocolo, seguido de sua descrição.
     * 
     * Exemplo: SMS - Secretaria Municipal de Saúde
     * 
     * @return string
     */
    public function getSiglaDescricao()
    {
        $siglaDescricao = $this->_nome;
        if ($this->_descricao) {
            $siglaDescricao .= ' - ' . $this->_descricao;
        }
        
        return $siglaDescricao;
    }
}