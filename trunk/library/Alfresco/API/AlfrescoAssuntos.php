<?php

require_once('AlfrescoBase.php');

class AlfrescoAssuntos extends AlfrescoBase
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
	
	public function getAssuntosPorTipoProcesso($nomeTipoProcesso)
	{
	    $nomeTipoProcesso = $this->_getNomeAjustadoNomeParaUrl($nomeTipoProcesso);
	    $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/listarportipoprocesso/$nomeTipoProcesso";
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
}