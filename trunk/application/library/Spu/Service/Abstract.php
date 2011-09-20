<?php
class Spu_Service_Abstract
{
    protected $_service;
        
    public function __construct($ticket = null)
    {
    	$this->_service = new Alfresco_Rest_Service(self::getBaseUrl());
        if (isset($ticket)) {
            $this->setTicket($ticket);
        }
    }
    
    public static function getBaseUrl()
    {
    	$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
    	return $config->get('alfresco')->get('url') . '/service';
    }
    
    public function getTicket()
    {
        return $this->_service->getTicket();
    }
    
    public function setTicket($ticket)
    {
        $this->_service->setTicket($ticket);
    }
    
    public function addAlfTicketUrl($url)
    {
        return $this->_service->addAlfTicketUrl($url);
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
    
    protected function _doAuthenticatedPostFormDataRequest($url, $postData)
    {
    	$url = $this->addAlfTicketUrl($url);
    	return $this->_doPostFormDataRequest($url, $postData);
    }
    
    protected function _doAuthenticatedGetRequest($url)
    {
    	$url = $this->addAlfTicketUrl($url);
    	return $this->_doGetRequest($url);
    }
    
    protected function _doAuthenticatedGetStringRequest($url)
    {
    	$url = $this->addAlfTicketUrl($url);
    	return $this->_doGetStringRequest($url);
    }
    
    protected function _doGetStringRequest($url)
    {
    	return $this->_service->doGetStringRequest($url);
    }
    
    protected function _doPostRequest($url, $postData)
    {
    	return $this->_service->doPostRequest($url, $postData);
    }
    
    protected function _doPostFormDataRequest($url, $postData)
    {
    	return $this->_service->doPostFormDataRequest($url, $postData);
    }
    
    protected function _doGetRequest($url)
    {
    	return $this->_service->doGetRequest($url);
    }
}