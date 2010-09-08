<?php
require_once('../library/Alfresco/Service/Repository.php');
class Alfresco
{
    const REPOSITORY_URL = 'http://localhost:8080/alfresco/api';
    protected $_ticket;
    protected $_repository;
    
    public function __construct($ticket)
    {
        $this->setTicket($ticket);
        $this->setRepository(new AlfRepository(self::REPOSITORY_URL));
    }
    
    protected function getTicket()
    {
        return $this->_ticket;
    }
    
    protected function setTicket($ticket)
    {
        $this->_ticket = $ticket;
    }
    
    protected function getRepository()
    {
        return $this->_repository;
    }
    
    protected function setRepository($repository)
    {
        $this->_repository = $repository;
    }
    
    protected function getSession()
    {
        try {
            $session = new AlfSession($this->getRepository(), $this->getTicket());
            return $session;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
?>