<?php
require_once('BaseDao.php');
Loader::loadEntity('TipoAbrangencia');
class TipoAbrangenciaDao extends BaseDao
{
    private $_baseUrl = 'spu/tiposprocesso';
    private $_ticketUrl = 'ticket';
    
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/abrangencias/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Abrangencias'][0];
    }
    
    protected function _loadFromHash($hash)
    {
        $tipoAbrangencia = new TipoAbrangencia();
        
        $tipoAbrangencia->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $tipoAbrangencia->setNome($this->_getHashValue($hash, 'nome'));
        $tipoAbrangencia->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $tipoAbrangencia;
    }
    
    protected function _loadManyFromHash($hash)
    {
        $tiposAbrangencia = array();
        foreach ($hash[0] as $hashTipoAbrangencia) {
            $tiposAbrangencia[] = $this->_loadFromHash($hashTipoAbrangencia[0]);
        }
        
        return $tiposAbrangencia;
    }
}