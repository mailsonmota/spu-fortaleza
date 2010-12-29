<?php
require_once('BaseEntity.php');
Loader::loadDao('ArquivoDao');
class Arquivo extends BaseEntity
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
    
    protected function _getDao()
    {
        return new ArquivoDao($this->_getTicket());
    }
    
    public function getArquivos($Uuid)
    {
        $dao = $this->_getDao();
        $arquivos = $dao->getArquivos($Uuid);
        
        $arquivosReturn = Array();
        foreach ($arquivos as $arquivo) {
             $arquivoAux = new Arquivo();
             $arquivoAux->setId($arquivo['id']);
             $arquivoAux->setNome($arquivo['nome']);
             $arquivosReturn[] = $arquivoAux;
        }
        
        return $arquivosReturn;
    }
    
    public function getArquivoDownloadUrl($arquivoHash)
    {
        $dao = $this->_getDao();
        return $dao->getArquivoDownloadUrl($arquivoHash);
    }
}