<?php
require_once('BaseDao.php');
Loader::loadEntity('Prioridade');
class PrioridadeDao extends BaseDao
{
    private $_baseUrl = 'spu/processo';
    private $_ticketUrl = 'ticket';
    
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/prioridades/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $this->_loadManyFromHash($result['Prioridades'][0]);
    }
    
    protected function _loadFromHash($hash)
    {
        $prioridade = new Prioridade();
        
        $prioridade->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $prioridade->setNome($this->_getHashValue($hash, 'nome'));
        $prioridade->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $prioridade;
    }
    
    protected function _loadManyFromHash($hash)
    {
        $prioridades = array();
        foreach ($hash[0] as $hashPrioridade) {
            $prioridades[] = $this->_loadFromHash($hashPrioridade[0]);
        }
        
        return $prioridades;
    }
}