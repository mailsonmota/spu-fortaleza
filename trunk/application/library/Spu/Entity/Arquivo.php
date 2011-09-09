<?php
class Spu_Entity_Arquivo extends Spu_Entity_Abstract
{
    protected $_id;
    protected $_nome;
    protected $_mimetype;
    protected $_downloadUrl;
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function setId($value)
    {
        $this->_id = $value;
    }
    
    public function getNome()
    {
        return $this->_nome;
    }
    
    public function setNome($value)
    {
        $this->_nome = $value;
    }
    
    public function getMimetype()
    {
        return $this->_mimetype;
    }
    
    public function setMimetype($value)
    {
        $this->_mimetype = $value;
    }
}