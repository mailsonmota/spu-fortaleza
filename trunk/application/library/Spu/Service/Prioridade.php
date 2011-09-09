<?php
class Spu_Service_Prioridade extends Spu_Service_Abstract
{
    private $_baseUrl = 'spu/processo';
    private $_ticketUrl = 'ticket';
    
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/prioridades/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);
        
        return $this->_loadManyFromHash($result['Prioridades'][0]);
    }
    
    public function loadFromHash($hash)
    {
        $prioridade = new Spu_Entity_Classification_Prioridade();
        
        $prioridade->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $prioridade->setNome($this->_getHashValue($hash, 'nome'));
        $prioridade->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $prioridade;
    }
    
    protected function _loadManyFromHash($hash)
    {
        $prioridades = array();
        foreach ($hash as $hashPrioridade) {
            $prioridades[] = $this->loadFromHash($hashPrioridade[0]);
        }
        
        return $prioridades;
    }
}