<?php
require_once('LinkProcesso.php');
require_once('../library/Alfresco/API/AlfrescoProcesso.php');
class CopiaProcesso extends LinkProcesso
{
	public function listar()
	{
		$service = new AlfrescoProcesso(self::ALFRESCO_URL, $this->_getTicket());
        $hashCopias = $service->getCopias();
        
        return $this->_loadManyFromHash($hashCopias);
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