<?php
require_once('BaseDao.php');
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
        
        return $result['Status'][0];
	}
}