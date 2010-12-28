<?php
require_once('BaseClassification.php');
Loader::loadDao('TipoTramitacaoDao');
class TipoTramitacao extends BaseClassification
{
    public function listar()
    {
        $dao = $this->_getDao();
        $hashDeTipoTramitacao = $dao->getTramitacoes();
        
        $tiposTramitacao = array();
        foreach ($hashDeTipoTramitacao as $hashTipoTramitacao) {
            $tipoTramitacao = new TipoTramitacao($this->_getTicket());
            $tipoTramitacao->loadFromHash($hashTipoTramitacao[0]);
            $tiposTramitacao[] = $tipoTramitacao;
        }
        
        return $tiposTramitacao;
    }
    
    protected function _getDao()
    {
        $dao = new TipoTramitacaoDao(self::ALFRESCO_URL, $this->_getTicket());
        return $dao;
    }
}