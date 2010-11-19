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
	
	public function getCaixaSaida()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/saida";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Processos'][0];
	}
	
	public function getCaixaAnalise()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/analise";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Processos'][0];
	}
	
	public function getCaixaEnviados()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/enviados";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
		if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result['Processos'][0];
	}
	
	public function getCaixaExternos()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/externos";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
		if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result['Processos'][0];
	}
	
	public function abrirProcesso($postData)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/abrir";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
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
	
	public function tramitar($postData)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/tramitar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
	}
	
	public function tramitarVarios($postData)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/tramitarProcessos";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
	}
	
	public function receberVarios($postData)
	{
		$url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/receber";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
		if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
	}
	
	public function tramitarExternos($postData)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/tramitarExternos";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
	}
	
	public function retornarExternos($postData)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/retornarExternos";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
	}
	
	public function getHistorico($nodeUuid)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/historico/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
		if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result['Processo'][0];
	}
}