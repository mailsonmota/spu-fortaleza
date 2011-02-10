<?php
require_once('BaseService.php');
class StatusService extends BaseService
{
    private $_baseUrl = 'spu/processo';
    private $_ticketUrl = 'ticket';
    
    public function loadFromHash($hash)
    {
        $status = new Status();
        
        $status->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $status->setNome($this->_getHashValue($hash, 'nome'));
        $status->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $status;
    }
}