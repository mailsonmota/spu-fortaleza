<?php
require_once('../library/Alfresco/API/AlfrescoBairros.php');
require_once('BaseAlfrescoClassificationEntity.php');
class Bairro extends BaseAlfrescoClassificationEntity
{
    public function listar()
    {
        $service = new AlfrescoBairros(self::ALFRESCO_URL, $this->_getTicket());
        $hashDeBairros = $service->getBairros();
        
        $bairros = array();
        foreach ($hashDeBairros[0] as $hashBairro) {
            $bairro = new Bairro($this->_getTicket());
            $bairro->loadFromHash($hashBairro[0]);
            $bairros[] = $bairro;
        }
        
        return $bairros;
    }
}
?>