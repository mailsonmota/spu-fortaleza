<?php

require_once('AlfrescoBase.php');
class AlfrescoProtocolo extends AlfrescoBase
{
	private $_protocoloBaseUrl = 'spu/protocolo';
	private $_protocoloTicketUrl = 'ticket';
	
	public function getProtocolos()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl . "/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Protocolos'][0];
	}
}