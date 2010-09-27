<?php

require_once('AlfrescoBase.php');

class AlfrescoProcesso extends AlfrescoBase
{
	private $_processoBaseUrl = 'spu/processo';
	private $_processoTicketUrl = 'ticket';
	
	public function getCaixaEntrada()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/entrada";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Processos'][0];
	}
}