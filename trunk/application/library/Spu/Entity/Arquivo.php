<?php
require_once('BaseEntity.php');

class Arquivo extends BaseEntity
{
    protected $_nome;
    protected $_mimetype;
    protected $_downloadUrl;
    
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
    
    public function getDownloadUrl()
    {
        return $this->_downloadUrl;
    }
    
    public function setDownloadUrl($value)
    {
        $this->_downloadUrl = $value;
    }
}
?>