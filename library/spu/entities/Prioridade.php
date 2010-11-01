<?php
require_once('../library/Alfresco/API/AlfrescoTiposProcesso.php');
require_once('BaseAlfrescoClassificationEntity.php');
class Prioridade extends BaseAlfrescoClassificationEntity
{
    public function listar()
    {
        $service = new AlfrescoProcesso(self::ALFRESCO_URL, $this->_getTicket());
        $hashDePrioridade = $service->getPrioridades();
        
        $prioridades = array();
        foreach ($hashDePrioridade as $hashPrioridade) {
            $prioridade = new Prioridade($this->_getTicket());
            $prioridade->loadFromHash($hashPrioridade[0]);
            $prioridades[] = $prioridade;
        }
        
        return $prioridades;
    }
}
?>