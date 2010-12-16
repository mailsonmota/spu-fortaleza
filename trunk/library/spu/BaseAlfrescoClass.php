<?php
class BaseAlfrescoClass
{
	const ALFRESCO_URL = 'http://172.30.116.21:8080/alfresco/service';
    protected $_ticket;
    
	public function __construct($ticket = null)
    {
        $this->_setTicket($ticket);
    }
    
    protected function _getTicket()
    {
        return $this->_ticket;
    }
    
    protected function _setTicket($ticket)
    {
        $this->_ticket = $ticket;
    }
    
    // FIXME
    public function changeTicket($ticket)
    {
    	$this->_setTicket($ticket);
    }
    
	public function __get($property) {
        $methodName = 'get' . ucwords($property);
        return $this->$methodName();
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