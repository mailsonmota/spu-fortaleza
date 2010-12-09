<?php
require_once('../library/Alfresco/API/AlfrescoProcesso.php');
require_once('BaseAlfrescoClassificationEntity.php');
class StatusArquivamento extends BaseAlfrescoClassificationEntity
{
	public function listar()
    {
        $service = new AlfrescoProcesso(self::ALFRESCO_URL, $this->_getTicket());
        $hashDeStatus = $service->getStatusArquivamento();
        
        $arrayStatus = array();
        foreach ($hashDeStatus as $hashStatus) {
            $status = new StatusArquivamento($this->_getTicket());
            $status->loadFromHash($hashStatus[0]);
            $arrayStatus[] = $status;
        }
        
        return $arrayStatus;
    }
}
?>