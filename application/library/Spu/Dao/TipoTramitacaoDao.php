<?php
require_once('BaseDao.php');
Loader::loadEntity('TipoTramitacao');
class TipoTramitacaoDao extends BaseDao
{
    private $_baseUrl = 'spu/tiposprocesso';
    private $_ticketUrl = 'ticket';
    
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/tramitacoes/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Tramitacoes'][0];
    }
    
    protected function _loadFromHash($hash)
    {
        $tipoTramitacao = new TipoTramitacao();
        
        $tipoTramitacao->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $tipoTramitacao->setNome($this->_getHashValue($hash, 'nome'));
        $tipoTramitacao->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $tipoTramitacao;
    }
    
    protected function _loadManyFromHash($hash)
    {
        $tiposTramitacao = array();
        foreach ($hash[0] as $hashTipoTramitacao) {
            $tiposTramitacao[] = $this->_loadFromHash($hashTipoTramitacao[0]);
        }
        
        return $tiposTramitacao;
    }
}