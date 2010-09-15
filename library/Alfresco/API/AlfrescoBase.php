<?php

require_once('CurlClient.php');

class AlfrescoBase
{
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
}