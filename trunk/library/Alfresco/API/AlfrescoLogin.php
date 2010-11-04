<?php

require_once('AlfrescoBase.php');

class AlfrescoLogin extends AlfrescoBase
{
	private $_loginBaseUrl = 'login';
	private $_loginTicketUrl = 'ticket';
	
	public function __construct($url) {
	   $this->setBaseUrl($url);
	}
	
	/*
	 * Login
	 * GET /alfresco/service/api/login?u={username}&pw={password?}
	 * 
	 * returns
	 * Assoc array $array['ticket' => ticket]
	 */
	public function login($username, $password)
	{
		$url =
		    $this->getBaseUrl() . "/api/" .
		    $this->_loginBaseUrl .
            "?u=" . $username . "&pw=" . $password . '&format=json';
        
		$curlObj = new CurlClient();
		
		$result = trim($curlObj->doGetRequest($url));
		$this->setTicket($result);
		
		$resultJson = json_decode($result);
		$ticket = $resultJson->data->ticket;
		
		return array('ticket' => $ticket);
	}
	
    /*
     * Logout
     * DELETE /alfresco/service/api/login/ticket/{ticket}
     */
	public function logout($ticket) {
	    $url =
	        $this->getBaseUrl() . "/api/" .
	        $this->_loginBaseUrl . "/" .
            $this->_loginTicketUrl . "/" .
            $ticket;
        
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doDeleteRequest($url);
        return $result;
        // TODO configurar retorno
	}
	
	/*
     * Validates the specified ticket is still valid. 
     * The ticket may be invalid, or expired, or the user may have been locked out. 
     * For security reasons this script will not validate the ticket of another user.
	 * GET /alfresco/service/api/login/ticket/<ticket>?alf_ticket=<ticket>
	 * 
	 * returns
     * If the ticket is valid retuns, STATUS_SUCCESS (200)
     * If the ticket is not valid return, STATUS_NOT_FOUND (404)
     * If the ticket does not belong to the current user, STATUS_NOT_FOUND (404)
     * 
     * FIXME alfresco login validate
     */
	public function validate() {
	    $url =
	        $this->getBaseUrl() . "/api/" .
            $this->_loginBaseUrl. "/" .
            $this->_loginTicketUrl . "/" .
            $this->getTicket();
        
        $url = $this->addAlfTicketUrl($url);
        var_dump($url); exit;
        $curlObj = new CurlClient();
        
        $result = $curlObj->doRequest($curlOptions);
        var_dump($result);
        return $result;
	}
}
