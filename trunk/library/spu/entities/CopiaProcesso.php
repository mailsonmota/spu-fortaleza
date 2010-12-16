<?php
require_once('LinkProcesso.php');
Loader::loadDao('CopiaProcessoDao');
class CopiaProcesso extends LinkProcesso
{
	public function listar()
	{
		$dao = $this->_getDao();
        $hashCopias = $dao->getCopias();
        
        return $this->_loadManyFromHash($hashCopias);
    }
    
    protected function _getDao()
    {
    	$dao = new CopiaProcessoDao(self::ALFRESCO_URL, $this->_getTicket());
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
}