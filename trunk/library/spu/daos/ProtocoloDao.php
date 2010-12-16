<?php
require_once('BaseDao.php');
class ProtocoloDao extends BaseDao
{
	private $_protocoloBaseUrl = 'spu/protocolo';
	private $_protocoloTicketUrl = 'ticket';
	
	public function getProtocolo($nodeUuid)
	{
		$url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl . "/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Protocolo'][0];		
	}
	
	public function getProtocolos()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl .  "/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Protocolos'][0];
	}
	
	public function getTodosProtocolos()
	{
		$url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl . "/listarTodos";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Protocolos'][0];
	}
}