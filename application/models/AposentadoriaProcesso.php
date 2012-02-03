<?php

class Application_Model_AposentadoriaProcesso extends Application_Model_Abstract
{

    public function __construct()
    {
        $this->_dbTable = new Application_Model_DbTable_AposentadoriaProcesso();
    }

    public function atualizar(array $dados)
    {
        $where = $this->_dbTable->getAdapter()
            ->quoteInto($this->_dbTable->getPrimary() . ' = ?', $dados['id']);
        unset($dados['id']);

        return $this->_dbTable->update($dados, $where);
    }

}