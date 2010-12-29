<?php
require_once('BaseEntity.php');
Loader::loadDao('ArquivoDao');
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
             $arquivoTmp = new Arquivo();
             $arquivoTmp->setNome($arquivo['nome']);
             //$arquivoTmp->setDownloadUrl($dao->getBaseUrl() . $arquivo['download']);
             $arquivoTmp->setDownloadUrl("http://172.30.116.21:8080/alfresco/service/api/node/workspace/SpacesStore/" . $arquivo['id'] . "/content/" . $arquivo['nome'] . "?alf_ticket=" . $this->_getTicket());
             $arquivosReturn[] = $arquivoTmp;
        }
        
        return $arquivosReturn;
    }
}