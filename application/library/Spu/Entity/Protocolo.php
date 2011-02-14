<?php
require_once('BaseEntity.php');
class Protocolo extends BaseEntity
{
    protected $_nodeRef;
    protected $_nome;
    protected $_descricao;
    protected $_orgao;
    protected $_lotacao;
    protected $_recebePelosSubsetores;
    protected $_recebeMalotes;
    protected $_parent;
    protected $_nivel;
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

    public function getRecebePelosSubsetores()
    {
        return $this->_recebePelosSubsetores;
    }

    public function setRecebePelosSubsetores($value)
    {
        $this->_recebePelosSubsetores = $value;
    }

    public function getRecebeMalotes()
    {
        return $this->_recebeMalotes;
    }

    public function setRecebeMalotes($value)
    {
        $this->_recebeMalotes = $value;
    } 
    
    public function getNivel()
    {
        return $this->_nivel;
    }

    public function setNivel($value)
    {
        $this->_nivel = $value;
    }
    
    public function getPath()
    {
        return $this->_path;
    }

    public function setPath($value)
    {
        $this->_path = $value;
    }

    public function getParent()
    {
        return $this->_parent;
    }

    public function setParent(Protocolo $value)
    {
        $this->_parent = $value;
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
            $orgaoLotacao = ($this->getDescricao()) ? $this->getDescricao() : $this->getNome();
        }
        return $orgaoLotacao;
    }
    
    public function getSiglaDescricao()
    {
    	$siglaDescricao = $this->_nome;
    	if ($this->_descricao) {
    		$siglaDescricao .= ' - ' . $this->_descricao;
    	}
    	
    	return $siglaDescricao;
    }
}