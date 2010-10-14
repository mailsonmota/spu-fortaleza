<?php

require_once('AlfrescoBase.php');

class AlfrescoBairros extends AlfrescoBase
{
	private $_bairrosBaseUrl = 'spu/bairros';
	private $_bairrosTicketUrl = 'ticket';
	
	public function getBairros()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_bairrosBaseUrl . "/listar";
	    $url = $this->addAlfTicketUrl($url);
	    
	    $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Bairros'];
	}
}