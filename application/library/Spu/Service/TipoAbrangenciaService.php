<?php
require_once('BaseService.php');
Loader::loadEntity('TipoAbrangencia');
class TipoAbrangenciaService extends BaseService
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
        
        return $this->_loadManyFromHash($result['Abrangencias'][0]);
    }
    
    public function loadFromHash($hash)
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
        foreach ($hash as $hashTipoAbrangencia) {
            $tiposAbrangencia[] = $this->loadFromHash($hashTipoAbrangencia[0]);
        }
        
        return $tiposAbrangencia;
    }
}