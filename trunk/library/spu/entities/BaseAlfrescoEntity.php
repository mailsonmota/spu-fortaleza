<?php
require_once('BaseEntity.php');
class BaseAlfrescoEntity extends Base
{
    const ALFRESCO_URL = 'http://localhost:8080/alfresco/service';
    protected $_ticket;
    protected $_repository;
    
    public function __construct($ticket)
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
}
?>