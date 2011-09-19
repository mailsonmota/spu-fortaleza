<?php
class Spu_Service_StatusArquivamento extends Spu_Service_Abstract
{
    private $_baseUrl = 'spu/processo';
    private $_ticketUrl = 'ticket';
    
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/statusarquivamento/listar";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Status'][0]);
    }
    
    public function loadFromHash($hash)
    {
        $statusArquivamento = new Spu_Entity_Classification_StatusArquivamento();
        
        $statusArquivamento->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $statusArquivamento->setNome($this->_getHashValue($hash, 'nome'));
        $statusArquivamento->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $statusArquivamento;
    }
    
    protected function _loadManyFromHash($hash)
    {
        $statusArquivamento = array();
        foreach ($hash as $hashStatusArquivamento) {
            $statusArquivamento[] = $this->loadFromHash($hashStatusArquivamento[0]);
        }
        
        return $statusArquivamento;
    }
}