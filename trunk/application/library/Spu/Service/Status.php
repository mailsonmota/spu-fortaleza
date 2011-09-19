<?php
class Spu_Service_Status extends Spu_Service_Abstract
{
    private $_baseUrl = 'spu/processo';
    private $_ticketUrl = 'ticket';
    
    public function listar()
    {
    	$url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/status/listar";
    	$result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Status'][0]);
    }
    
    protected function _loadManyFromHash($hash)
    {
        $status = array();
        foreach ($hash as $hashStatus) {
            $status[] = $this->loadFromHash($hashStatus[0]);
        }
        
        return $status;
    }
    
    public function loadFromHash($hash)
    {
        $status = new Spu_Entity_Classification_Status();
        
        $status->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $status->setNome($this->_getHashValue($hash, 'nome'));
        $status->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $status;
    }
}