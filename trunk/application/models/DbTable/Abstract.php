<?php

abstract class Application_Model_DbTable_Abstract extends Zend_Db_Table_Abstract
{
    protected $_name;
    protected $_primary;
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function getPrimary()
    {
        return $this->_primary;
    }

}
