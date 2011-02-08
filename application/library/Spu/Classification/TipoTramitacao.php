<?php
require_once('BaseClassification.php');
Loader::loadDao('TipoTramitacaoDao');
class TipoTramitacao extends BaseClassification
{
	const PARALELA = 'Paralelo';
	
    public function listar()
    {
        $dao = $this->_getDao();
        $hashDeTipoTramitacao = $dao->fetchAll();
        
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
        $dao = new TipoTramitacaoDao($this->_getTicket());
        return $dao;
    }
    
    public function isParalela()
    {
    	return ($this->_nome == self::PARALELA);
    }
}