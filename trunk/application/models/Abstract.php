<?php

abstract class Application_Model_Abstract
{

    protected $_dbTable;

    public function encontrar($id)
    {
        return $this->_dbTable->find($id)->current();
    }

    public function quantidade()
    {
        $select = $this->_dbTable->select()->from($this->_dbTable->getName(), 'COUNT(*) as count');

        return $this->_dbTable->fetchRow($select)->count;
    }
    
    public function inserir($dados)
    {
        return $this->_dbTable->insert($dados);
    }
}
