<?php
require_once('BaseDao.php');
class ProcessoDao extends BaseDao
{
	private $_processoBaseUrl = 'spu/processo';
	private $_processoTicketUrl = 'ticket';
	
	public function getCaixaEntrada()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/entrada";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_getProcessosFromUrl($url);
	}
	
	public function getCaixaSaida()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/saida";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_getProcessosFromUrl($url);
	}
	
	public function getCaixaAnalise()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/analise";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_getProcessosFromUrl($url);
	}
	
	public function getCaixaEnviados()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/enviados";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_getProcessosFromUrl($url);
	}
	
	public function getCaixaExternos()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/externos";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_getProcessosFromUrl($url);
	}
	
	public function getCaixaArquivo()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivo";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_getProcessosFromUrl($url);
	}
	
	protected function _getProcessosFromUrl($url)
	{
		$result = $this->_getResultFromUrl($url);
        return $result['Processos'][0];
	}
	
	protected function _getResultFromUrl($url)
	{
		$curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
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
    
	public function getProcesso($nodeUuid)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Processo'][0];
	}
	
	/*
	 * Estrutura de $data
	 *   - $data['processoId']
	 *   - $data['fileContent']
	 */
	public function uploadArquivo($postData)
	{
		// TODO Revisar web script "uploadarquivo"
		$url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/uploadarquivo";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData, 'formdata');
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
	
    /*  public function uploadArquivo($postData)
    {
        // $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/uploadarquivo";
        $url = $this->getBaseUrl() . "/api/upload";
        // $url = $this->getBaseUrl() . "/spu/processo/uploadarquivo";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData, 'formdata');
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }*/
    
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
	
	public function cancelarEnvios($postData)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/cancelarEnvios";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
	}
	
	public function incorporar($data)
	{
		$url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/incorporar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doPostRequest($url, $data);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
	}
	
	public function arquivarVarios($postData)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
	}
	
	public function reabrirVarios($postData)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/reabrir";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
	}
	
	public function comentarVarios($postData)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/comentar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
	}
	
	public function consultar($postData)
	{
	    $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/consultar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
        	throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result['Processos'][0];
	}
	
	public function getArquivos($nodeUuid)
	{
		$url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivos/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
	}
}