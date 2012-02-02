<?php

class Application_Model_AposentadoriaProcesso
{

    protected $_db = null;

    public function __construct()
    {
        $this->_db = new Application_Model_DbTable_AposentadoriaProcesso();
    }

    public function count()
    {
        $select = $this->_db->select()->from($this->_db->getName(), 'COUNT(*) as count');

        return $this->_db->fetchRow($select)->count;
    }

    public function inserir($dados)
    {
        $this->_db->insert($dados);
    }
    
    public function last()
    {
        $select = $this->_db->select()->limit(1)->order("PRONTUARIO DESC");
        
        return $this->_db->fetchRow($select);
    }

}

