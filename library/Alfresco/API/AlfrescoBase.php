<?php

require_once('CurlClient/CurlClient.php');

class AlfrescoBase
{
    const DEFAULT_ADAPTER = 'json';
    
	private $_baseUrl;
	private $_ticket;
	
	public function __construct($url, $ticket = null) {
		$this->setBaseUrl($url);
		if (isset($ticket)) {
			$this->setTicket($ticket);
		}
	}
	
    public function getBaseUrl() {
        return $this->_alfrescoBaseUrl;
    }
    
	public function setBaseUrl($alfrescoBaseUrl) {
		$this->_alfrescoBaseUrl = $alfrescoBaseUrl;
	}
	
	public function getTicket() {
        return $this->_ticket;
    }
    
    public function setTicket($ticket) {
        $this->_ticket = $ticket;
    }
    
    /*
     * Put an "alf_ticket=<ticket>" on the given URL
     */
    public function addAlfTicketUrl($url) {
        $ticket = $this->getTicket();
        if (isset($ticket)) {
        	if (strstr($url, '?')) {
                $url .= "&alf_ticket=" . $ticket;
        	} else {
        		$url .= "?alf_ticket=" . $ticket;
        	}
        }
        return $url;
    }
    
    /*
     * Desuso por causa do Adapter
     */
    public function postToJson($postArgs) {
        $json  = "{";
        $json .= "\"name\" : \"" . $postArgs['name'] . "\",";
//        $json += "\"data\" : \"2030-11-11\",";
//        $json += "observacao" : "observacao 1",
//        $json += "prioridade" : "Ordinario (Normal)",
//        $json += "numero_origem" : "numero origem 12",
//        $json += "data_prazo" : "2030-11-11",
//        $json += "manifestante_nome" : "nome do manifestante",
//        $json += "manifestante_cpf" : "cpf do manifestante",
//        $json += "manifestante_tipo" : "tipo do manifestante",
//        $json += "manifestante_bairro" : "bairro do manifestante",
//        $json += "assunto" : "assunto",
        $json .= "}";
        
        return $json;
    }
    
    public function isAlfrescoError($return) {
    	if (!empty($return['exception'])) {
    		return true;
    	}
    }
    
    public function getAlfrescoErrorMessage($return) {
    	if ($this->isAlfrescoError($return)) {
    		return $return['message'];
    	}
    }
}