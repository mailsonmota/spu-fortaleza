<?php

require_once('BaseAlfrescoEntity.php');
Loader::loadAlfrescoApiClass('AlfrescoPeople');

class Usuario extends BaseAlfrescoEntity
{
    private $_alfUsuarioObject;
    
    public function __construct($baseUrl, $ticket) {
        $this->_alfUsuarioObject = new AlfrescoPeople($baseUrl, $ticket); 
    }
    
    public function getPerson($userName) {
        return $this->_alfUsuarioObject->getPerson($userName);
    }
    
    public function getGroups($userName) {
    	return $this->_alfUsuarioObject->getGroups($userName);
    }
}