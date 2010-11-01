<?php
require_once('../library/Alfresco/API/AlfrescoTiposProcesso.php');
require_once('BaseAlfrescoClassificationEntity.php');
class TipoAbrangencia extends BaseAlfrescoClassificationEntity
{
    public function listar()
    {
        $service = new AlfrescoTiposProcesso(self::ALFRESCO_URL, $this->_getTicket());
        $hashDeTipoAbrangencia = $service->getAbrangencias();
        
        $tiposAbrangencia = array();
        foreach ($hashDeTipoAbrangencia as $hashTipoAbrangencia) {
            $tipoAbrangencia = new TipoAbrangencia($this->_getTicket());
            $tipoAbrangencia->loadFromHash($hashTipoAbrangencia[0]);
            $tiposAbrangencia[] = $tipoAbrangencia;
        }
        
        return $tiposAbrangencia;
    }
}
?>