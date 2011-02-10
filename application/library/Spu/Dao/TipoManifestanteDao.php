<?php
require_once('BaseDao.php');
class TipoManifestanteDao extends BaseDao
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
        
        return $result['Tipos de Manifestante'][0];
    }
    
    protected function _loadFromHash($hash)
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
        foreach ($hash[0] as $hashTipoManifestante) {
            $tiposManifestante[] = $this->_loadFromHash($hashTipoManifestante[0]);
        }
        
        return $tiposManifestante;
    }
}