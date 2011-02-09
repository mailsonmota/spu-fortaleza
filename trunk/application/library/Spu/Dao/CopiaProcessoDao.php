<?php
require_once('BaseDao.php');
Loader::loadEntity('CopiaProcesso');
class CopiaProcessoDao extends BaseDao
{
    private $_processoBaseUrl = 'spu/processo';
    private $_processoTicketUrl = 'ticket';
    
    public function getCopias($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/copias/$offset/$pageSize/$filter";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_loadManyFromHash($this->_getCopiasFromUrl($url));
    }
    
    protected function _getCopiasFromUrl($url)
    {
        $result = $this->_getResultFromUrl($url);
        return $result['Copias'][0];
    }
    
    public function excluirTodos($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/copias/excluir";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
    
    protected function _loadFromHash($hash)
    {
    	$copia = new CopiaProcesso($this->getTicket());
    	
        $copia->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $copia->setNome($this->_getHashValue($hash, 'nome'));
        $copia->setProcesso($this->_loadProcessoFromHash($this->_getHashValue($hash, 'Processo')));
        
        return $copia;
    }
    
    protected function _loadProcessoFromHash($hash)
    {
        $hashProcesso = array_pop($hash);
        $hashProcesso = array_pop($hashProcesso);
        $hashDadosProcesso = array_pop($hashProcesso);
        $processo = new Processo();
        $processo->loadFromHash($hashDadosProcesso);
        return $processo;
    }
    
    protected function _loadManyFromHash($hashCopias)
    {
        $copias = array();
        foreach ($hashCopias as $hashCopia) {
        	$hashCopia = array_pop($hashCopia);
            $copias[] = $this->_loadFromHash($hashCopia);
        }
        
        return $copias;
    }
}