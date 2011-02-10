<?php
class BaseService
{
    const ALFRESCO_URL = 'http://172.30.116.21:8080/alfresco/service';
    const ALFRESCO_BASE_URL = 'http://172.30.116.21:8080/alfresco';
    
    protected $_service;
        
    public function __construct($ticket = null) {
        $this->_service = new Alfresco_Rest_Service(self::ALFRESCO_URL);
        if (isset($ticket)) {
            $this->setTicket($ticket);
        }
    }
    
    public function getBaseUrl() {
        return self::ALFRESCO_URL;
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
}