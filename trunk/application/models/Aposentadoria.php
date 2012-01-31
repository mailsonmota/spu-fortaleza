<?php

class Application_Model_Aposentadoria
{

    protected $_db = null;

    public function __construct()
    {
        $this->_db = new Application_Model_DbTable_Aposentadoria();
    }

    public function count()
    {
        $select = $this->_db->select()->from($this->_db->getName(), 'COUNT(*) as count');

        return $this->_db->fetchRow($select)->count;
    }

    public function inserir($dados)
    {
        try {
            var_dump($this->_db->insert($dados));
        } catch (Zend_Db_Adapter_Exception $e) {
            echo '<pre>';
            var_dump($e);
            echo '</pre>';
            die();
        } catch (Zend_Exception $e) {
            echo '<pre>';
            var_dump($e);
            echo '</pre>';
            die();
        }
    }
    
    public function last()
    {
        $select = $this->_db->select()->limit(1)->order("PRONTUARIO DESC");
        
        return $this->_db->fetchRow($select);
    }

}

