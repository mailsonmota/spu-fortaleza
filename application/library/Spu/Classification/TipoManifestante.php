<?php
require_once('BaseClassification.php');
Loader::loadDao('TipoManifestanteDao');
class TipoManifestante extends BaseClassification
{
    public function listar()
    {
        $dao = $this->_getDao();
        $hashDeTipoManifestante = $dao->fetchAll();
        
        $tiposManifestante = array();
        foreach ($hashDeTipoManifestante as $hashTipoManifestante) {
            $tipoManifestante = new TipoManifestante($this->_getTicket());
            $tipoManifestante->loadFromHash($hashTipoManifestante[0]);
            $tiposManifestante[] = $tipoManifestante;
        }
        
        return $tiposManifestante;
    }
    
    protected function _getDao()
    {
        $dao = new TipoManifestanteDao($this->_getTicket());
        return $dao;
    }
}