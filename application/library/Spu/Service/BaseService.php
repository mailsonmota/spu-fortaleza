<?php
class BaseService
{
    protected $_service;
        
    public function __construct($ticket = null) {
    	$this->_service = new Alfresco_Rest_Service(self::getBaseUrl());
        if (isset($ticket)) {
            $this->setTicket($ticket);
        }
    }
    
    public static function getBaseUrl() {
    	$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
    	return $config->get('alfresco')->get('url') . '/service';
    }
    
    public function getTicket() {
        return $this->_service->getTicket();
    }
    
    public function setTicket($ticket) {
        $this->_service->setTicket($ticket);
    }
    
    public function addAlfTicketUrl($url) {
        return $this->_service->addAlfTicketUrl($url);
    }
    
    public function isAlfrescoError($return) {
        return $this->_service->isAlfrescoError($return);
    }
    
    public function getAlfrescoErrorMessage($return) {
        return $this->_service->getAlfrescoErrorMessage($return);
    }
    
    protected function _getResultFromUrl($url)
    {
        return $this->_service->getResultFromUrl($url);
    }
    
    protected function _getHashValue($hash, $hashField)
    {
        if (!isset($hash[$hashField])) {
            return null;
        }
        
        if (is_array($hash[$hashField])) {
            $value = array();
            foreach ($hash[$hashField] as $hashValue) {
                $value[] = $hashValue;
            }
        } else {
            $value = $hash[$hashField];
        }
        return $value;
    }
    
    protected function _doAuthenticatedPostRequest($url, $postData)
    {
    	$url = $this->addAlfTicketUrl($url);
    	return $this->_doPostRequest($url, $postData);
    }
    
    protected function _doAuthenticatedGetRequest($url)
    {
    	$url = $this->addAlfTicketUrl($url);
    	return $this->_doGetRequest($url);
    }
    
    protected function _doPostRequest($url, $postData)
    {
    	$curlClient = $this->_getCurlClient();
    	return $curlClient->doPostRequest($url, $postData);
    }
    
    protected function _doGetRequest($url)
    {
    	$curlClient = $this->_getCurlClient();
    	return $curlClient->doGetRequest($url);
    }
    
    protected function _getCurlClient()
    {
    	return new CurlClient();
    }
}