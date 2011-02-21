<?php
require_once('BaseService.php');
class StatusService extends BaseService
{
    private $_baseUrl = 'spu/processo';
    private $_ticketUrl = 'ticket';
    
    public function listar()
    {
    	$url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/status/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
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
        $status = new Status();
        
        $status->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $status->setNome($this->_getHashValue($hash, 'nome'));
        $status->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $status;
    }
}