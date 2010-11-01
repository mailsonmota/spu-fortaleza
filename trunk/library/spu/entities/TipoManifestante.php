<?php
require_once('../library/Alfresco/API/AlfrescoTiposProcesso.php');
require_once('BaseAlfrescoClassificationEntity.php');
class TipoManifestante extends BaseAlfrescoClassificationEntity
{
    public function listar()
    {
        $service = new AlfrescoTiposProcesso(self::ALFRESCO_URL, $this->_getTicket());
        $hashDeTipoManifestante = $service->getTiposManifestante();
        
        $tiposManifestante = array();
        foreach ($hashDeTipoManifestante as $hashTipoManifestante) {
            $tipoManifestante = new TipoManifestante($this->_getTicket());
            $tipoManifestante->loadFromHash($hashTipoManifestante[0]);
            $tiposManifestante[] = $tipoManifestante;
        }
        
        return $tiposManifestante;
    }
}
?>