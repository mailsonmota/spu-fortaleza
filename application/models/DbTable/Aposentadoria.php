<?php

class Application_Model_DbTable_Aposentadoria extends Application_Model_DbTable_Abstract
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_name = 'TB_AAP';
        $this->_primary = 'PRONTUARIO';
    }

}