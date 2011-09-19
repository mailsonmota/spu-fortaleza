<?php
class Spu_Service_TipoTramitacao extends Spu_Service_Abstract
{
    private $_baseUrl = 'spu/tiposprocesso';
    private $_ticketUrl = 'ticket';
    
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/tramitacoes/listar";
        
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Tramitacoes'][0]);
    }
    
    public function loadFromHash($hash)
    {
        $tipoTramitacao = new Spu_Entity_Classification_TipoTramitacao();
        
        $tipoTramitacao->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $tipoTramitacao->setNome($this->_getHashValue($hash, 'nome'));
        $tipoTramitacao->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $tipoTramitacao;
    }
    
    protected function _loadManyFromHash($hash)
    {
        $tiposTramitacao = array();
        foreach ($hash as $hashTipoTramitacao) {
            $tiposTramitacao[] = $this->loadFromHash($hashTipoTramitacao[0]);
        }
        
        return $tiposTramitacao;
    }
}