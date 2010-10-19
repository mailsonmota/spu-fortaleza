<?php
require_once('../library/Alfresco/API/AlfrescoTiposProcesso.php');
require_once('BaseAlfrescoClassificationEntity.php');
class TipoTramitacao extends BaseAlfrescoClassificationEntity
{
    public function listar()
    {
        $service = new AlfrescoTiposProcesso(self::ALFRESCO_URL, $this->_getTicket());
        $hashDeTipoTramitacao = $service->getTramitacoes();
        
        $tiposTramitacao = array();
        foreach ($hashDeTipoTramitacao as $hashTipoTramitacao) {
            $tipoTramitacao = new TipoTramitacao($this->_getTicket());
            $tipoTramitacao->_loadFromHash($hashTipoTramitacao[0]);
            $tiposTramitacao[] = $tipoTramitacao;
        }
        
        return $tiposTramitacao;
    }
}
?>