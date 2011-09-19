<?php
class Spu_Service_TipoAbrangencia extends Spu_Service_Abstract
{
    private $_baseUrl = 'spu/tiposprocesso';
    private $_ticketUrl = 'ticket';
    
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/abrangencias/listar";
        
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Abrangencias'][0]);
    }
    
    public function loadFromHash($hash)
    {
        $tipoAbrangencia = new Spu_Entity_Classification_TipoAbrangencia();
        
        $tipoAbrangencia->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $tipoAbrangencia->setNome($this->_getHashValue($hash, 'nome'));
        $tipoAbrangencia->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $tipoAbrangencia;
    }
    
    protected function _loadManyFromHash($hash)
    {
        $tiposAbrangencia = array();
        foreach ($hash as $hashTipoAbrangencia) {
            $tiposAbrangencia[] = $this->loadFromHash($hashTipoAbrangencia[0]);
        }
        
        return $tiposAbrangencia;
    }
}