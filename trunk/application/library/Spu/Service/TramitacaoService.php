<?php
require_once 'ProcessoService';
class TramitacaoService extends ProcessoService
{
	public function getCaixaEntrada($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/entrada/$offset/$pageSize/$filter";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaSaida($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/saida/$offset/$pageSize/$filter";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaAnalise($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/analise/$offset/$pageSize/$filter";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
	public function getCaixaEnviados($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/enviados/$offset/$pageSize/$filter";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaExternos()
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/externos";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaArquivo($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivo/$offset/$pageSize/$filter";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
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
}