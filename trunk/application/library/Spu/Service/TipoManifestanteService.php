<?php
require_once('BaseService.php');
class TipoManifestanteService extends BaseService
{
    private $_baseUrl = 'spu/tiposprocesso';
    private $_ticketUrl = 'ticket';
    
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/tiposmanifestante/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $this->_loadManyFromHash($result['Tipos de Manifestante'][0]);
    }
    
    public function loadFromHash($hash)
    {
        $tipoManifestante = new TipoManifestante();
        
        $tipoManifestante->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $tipoManifestante->setNome($this->_getHashValue($hash, 'nome'));
        $tipoManifestante->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $tipoManifestante;
    }
    
    protected function _loadManyFromHash($hash)
    {
        $tiposManifestante = array();
        foreach ($hash as $hashTipoManifestante) {
            $tiposManifestante[] = $this->loadFromHash($hashTipoManifestante[0]);
        }
        
        return $tiposManifestante;
    }
}