<?php
class Spu_Service_Tramitacao extends Spu_Service_Processo
{
	public function getCaixaEntrada($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/entrada/$offset/$pageSize/$filter";
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaSaida($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/saida/$offset/$pageSize/$filter";
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaAnalise($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/analise/$offset/$pageSize/$filter";
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
	public function getCaixaEnviados($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/enviados/$offset/$pageSize/$filter";
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaExternos($offset = 0, $pageSize = 20, $filter = '')
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/externos/$offset/$pageSize/$filter";
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaArquivo($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivo/$offset/$pageSize/$filter";
        
        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
	public function tramitar($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/tramitar";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $result;
    }
    
    public function tramitarVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/tramitarProcessos";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $result;
    }
    
    public function receberVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/receber";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $result;
    }
    
    public function tramitarExternos($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/tramitarExternos";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $result;
    }
    
    public function retornarExternos($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/retornarExternos";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $result;
    }
    
	public function cancelarEnvios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/cancelarEnvios";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $result;
    }
    
	public function arquivarVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivar";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $result;
    }
    
    public function reabrirVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/reabrir";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $result;
    }
    
    public function comentarVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/comentar";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $result;
    }
}