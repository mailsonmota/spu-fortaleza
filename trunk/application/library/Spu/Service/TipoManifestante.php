<?php
class Spu_Service_TipoManifestante extends Spu_Service_Abstract
{
    private $_baseUrl = 'spu/tiposprocesso';
    private $_ticketUrl = 'ticket';
    
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/tiposmanifestante/listar";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Tipos de Manifestante'][0]);
    }
    
    public function loadFromHash($hash)
    {
        $tipoManifestante = new Spu_Entity_Classification_TipoManifestante();
        
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