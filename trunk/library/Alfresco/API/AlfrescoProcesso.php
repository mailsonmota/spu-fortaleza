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
	
	public function abrirProcesso($postData)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/abrir";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $resultJson = $curlObj->doPostRequest($url, $postData);
        $result = json_decode($resultJson, true);
        
        return $result;
	}
	
    public function getPrioridades()
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/prioridades/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Prioridades'][0];
    }
    
	public function getProcesso($nodeUuid)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Processo'][0];
	}
}