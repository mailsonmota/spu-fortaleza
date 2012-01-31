<?php

class Application_Model_DbTable_Aposentadoria extends Zend_Db_Table_Abstract
{

    protected $_name = 'TB_AAP_PROCESSO';
    protected $_primary = 'PRONTUARIO';
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function getPrimary()
    {
        return $this->_primary;
    }

}

