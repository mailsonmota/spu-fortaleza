<?php

require_once('AlfrescoBase.php');

class AlfrescoManifestantes extends AlfrescoBase
{
	private $_manifestantesBaseUrl = 'spu/manifestantes';
	private $_manifestantesTicketUrl = 'ticket';
	
	public function getManifestantes()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_manifestantesBaseUrl . "/listar";
	    $url = $this->addAlfTicketUrl($url);
	    
	    $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Manifestantes'];
	}
	
    public function getManifestante($cpf)
    {
        $url = $this->getBaseUrl() . "/" . $this->_manifestantesBaseUrl . "/get/$cpf";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Manifestante'];
    }
}