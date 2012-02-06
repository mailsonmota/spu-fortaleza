<?php

class Application_Model_AposentadoriaProcesso extends Application_Model_Abstract
{

    public function __construct()
    {
        $this->_dbTable = new Application_Model_DbTable_AposentadoriaProcesso();
    }

    public function atualizar(array $dados, $where = null)
    {
        $w = $this->_dbTable->getAdapter()
            ->quoteInto($this->_dbTable->getPrimary() . ' = ?', $dados['id']);
        unset($dados['id']);
        
        if ($where)
            $w .= " AND $where";

        return $this->_dbTable->update($dados, $w);
    }

}