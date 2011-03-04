<?php
require_once('BaseEntity.php');
class Volume extends BaseEntity
{
    protected $_nome;
    protected $_inicio;
    protected $_fim;
    protected $_observacao;
    
    public function getNome()
    {
        return $this->_nome;
    }
    
    public function setNome($data)
    {
        $this->_nome = $data;
    }
    
    public function getInicio()
    {
        return $this->_inicio;
    }
    
    public function setInicio($data)
    {
        $this->_inicio = $data;
    }
    
    public function getFim()
    {
        return $this->_fim;
    }
    
    public function setFim($data)
    {
        $this->_fim = $data;
    }
    
    public function getObservacao()
    {
        return $this->_observacao;
    }
    
    public function setObservacao($data)
    {
        $this->_observacao = $data;
    }
}
