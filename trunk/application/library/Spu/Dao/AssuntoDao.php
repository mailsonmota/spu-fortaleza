<?php
require_once('BaseDao.php');
class AssuntoDao extends BaseDao
{
    private $_assuntosBaseUrl = 'spu/assuntos';
    private $_assuntosTicketUrl = 'ticket';
    
    public function getAssuntos()
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['assuntos'];
    }
    
    public function getAssuntosPorTipoProcesso($idTipoProcesso)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/listarportipoprocesso/$idTipoProcesso";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['assuntos'];
    }
    
    protected function _getNomeAjustadoNomeParaUrl($nome)
    {
        $nome = str_replace(' ', '%20', $nome);
        return $nome;
    }
    
    public function getAssunto($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Assunto'][0];
    }
    
    public function inserir($postData)
    {    	
    	$url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/inserir";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result['Assunto'][0];
    }
    
    public function editar($id, $postData)
    {       
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/editar/$id";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result['Assunto'][0];
    }
}