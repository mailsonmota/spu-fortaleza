<?php
require_once('BaseDao.php');
Loader::loadEntity('StatusArquivamento');
class StatusArquivamentoDao extends BaseDao
{
    private $_baseUrl = 'spu/processo';
    private $_ticketUrl = 'ticket';
    
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/statusarquivamento/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $this->_loadManyFromHash($result['Status'][0]);
    }
    
    public function loadFromHash($hash)
    {
        $statusArquivamento = new StatusArquivamento();
        
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