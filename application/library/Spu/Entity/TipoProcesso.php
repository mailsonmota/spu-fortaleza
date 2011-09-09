<?php
class Spu_Entity_TipoProcesso extends Spu_Entity_Abstract
{
    protected $_nodeRef;
    protected $_nome;
    protected $_titulo;
    protected $_simples;
    protected $_letra;
    protected $_tramitacao;
    protected $_abrangencia;
    protected $_observacao;
    protected $_envolvidoSigiloso;
    protected $_tiposManifestante;

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
    
    public function getTitulo()
    {
        return $this->_titulo;
    }
    
    public function setTitulo($titulo)
    {
        $this->_titulo = $titulo;
    }
    
    public function getSimples()
    {
        return $this->_simples;
    }
    
    public function setSimples($value)
    {
        $this->_simples = $value;
    }
    
    public function getLetra()
    {
        return $this->_letra;
    }
    
    public function setLetra($value)
    {
        $this->_letra = $value;
    }

    public function getTramitacao()
    {
        return $this->_tramitacao;
    }
    
    public function setTramitacao($value)
    {
        $this->_tramitacao = $value;
    }

    public function getAbrangencia()
    {
        return $this->_abrangencia;
    }
    
    public function setAbrangencia($value)
    {
        $this->_abrangencia = $value;
    }

    public function getObservacao()
    {
        return $this->_observacao;
    }
    
    public function setObservacao($value)
    {
        $this->_observacao = $value;
    }

    public function getEnvolvidoSigiloso()
    {
        return $this->_envolvidoSigiloso;
    }

    public function setEnvolvidoSigiloso($value)
    {
        $this->_envolvidoSigiloso = $value;
    }
    
    public function getTiposManifestante()
    {
        return $this->_tiposManifestante;
    }
    
    public function setTiposManifestante($value)
    {
        $this->_tiposManifestante = $value;
    }

    public function getId()
    {
        $nodeRef = $this->getNodeRef();
        return substr($nodeRef, strrpos($nodeRef, '/') + 1);
    }
}