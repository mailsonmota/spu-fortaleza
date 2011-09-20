<?php
class Spu_Service_CopiaProcesso extends Spu_Service_Abstract
{
    private $_processoBaseUrl = 'spu/processo';
    private $_processoTicketUrl = 'ticket';
    
    public function getCopias($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/copias/$offset/$pageSize/$filter";
        
        return $this->_loadManyFromHash($this->_getCopiasFromUrl($url));
    }
    
    protected function _getCopiasFromUrl($url)
    {
        $result = $this->_doAuthenticatedGetRequest($url);
        return $result['Copias'][0];
    }
    
    public function excluirTodos($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/copias/excluir";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
                
        return $result;
    }
    
    public function loadFromHash($hash)
    {
        $copia = new Spu_Entity_CopiaProcesso($this->getTicket());
        
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
        $processoService = new Spu_Service_Processo();
        $processo = $processoService->loadFromHash($hashDadosProcesso);
        return $processo;
    }
    
    protected function _loadManyFromHash($hashCopias)
    {
        $copias = array();
        foreach ($hashCopias as $hashCopia) {
            $hashCopia = array_pop($hashCopia);
            $copias[] = $this->loadFromHash($hashCopia);
        }
        
        return $copias;
    }
}