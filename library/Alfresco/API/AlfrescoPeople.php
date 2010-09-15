<?php

require_once('AlfrescoBase.php');

class AlfrescoPeople extends AlfrescoBase
{
	private $_peopleBaseUrl = 'people';
	private $_peoplePreferencesUrl = 'preferences';
	private $_peopleSitesUrl = 'sites';
	
	/*
	 * List users
	 */
	public function listPeople($filter = null) {
		$url =
            $this->getBaseUrl() . "/" .
            $this->_peopleBaseUrl;
        
        $url = $this->addAlfTicketUrl($url);
        
        if (isset($filter)) {
        	$url .= "&filter=" . $filter;
        }
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        return $result['people']; // $result['people'][0]['firstName']
	}
	

	/*
	 * Get person
	 * GET /alfresco/service/api/people/{userName}
	 * 
	 * returns
	 * Assoc array
	 */
	public function getPerson($userName) {
	    $url =
            $this->getBaseUrl() . "/" .
            $this->_peopleBaseUrl . "/" .
            $userName;
	    
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        return $result;
	}
}