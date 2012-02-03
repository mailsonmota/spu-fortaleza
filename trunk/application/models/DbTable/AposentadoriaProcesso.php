<?php

class Application_Model_DbTable_AposentadoriaProcesso extends Application_Model_DbTable_Abstract
{

    public function __construct()
    {
        parent::__construct();

        $this->_name = 'TB_AAP_PROCESSO';
        $this->_primary = 'PRONTUARIO';
    }

}