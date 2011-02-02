<?php
require_once('LinkProcesso.php');
Loader::loadDao('CopiaProcessoDao');
class CopiaProcesso extends LinkProcesso
{
    public function listar($offset = 0, $pageSize = 20, $filter = null)
    {
        $dao = $this->_getDao();
        $hashCopias = $dao->getCopias($offset, $pageSize, $filter);
        
        return $this->_loadManyFromHash($hashCopias);
    }
    
    protected function _getDao()
    {
        $dao = new CopiaProcessoDao($this->_getTicket());
        return $dao;
    }
    
    protected function _loadManyFromHash($hashCopias)
    {
        $copias = array();
        foreach ($hashCopias as $hashCopia) {
            $hashDadosCopia = array_pop($hashCopia); 
            $copia = new CopiaProcesso($this->_getTicket());
            $copia->loadFromHash($hashDadosCopia);
            $copias[] = $copia;
        }
        
        return $copias;
    }
    
    public function excluir($postData)
    {
        $dao = $this->_getDao();
        return $dao->deleteAll($postData);
    }
}