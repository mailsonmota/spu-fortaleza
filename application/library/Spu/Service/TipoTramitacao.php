<?php
/**
 * Classe para acessar os serviços de Tipo de Manifestante dos Processos do SPU
 *
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_TipoTramitacao extends Spu_Service_Abstract
{
    /**
	 * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
	 * @var string
	 */
    private $_baseUrl = 'spu/tiposprocesso';
    
    /**
     * Retorna todas as opções de tipo de abrangência
     *
     * @return Spu_Entity_Classification_TipoTramitacao[]
     */
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/tramitacoes/listar";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Tramitacoes'][0]);
    }
    
    /**
     * Carrega o tipo de tramitação através de um hash
     *
     * @param array $hash
     * @return Spu_Entity_Classification_TipoTramitacao
     */
    public function loadFromHash($hash)
    {
        $tipoTramitacao = new Spu_Entity_Classification_TipoTramitacao();
        
        $tipoTramitacao->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $tipoTramitacao->setNome($this->_getHashValue($hash, 'nome'));
        $tipoTramitacao->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $tipoTramitacao;
    }
    
    /**
     * Carrega vários tipos de tramitação através de um hash
     *
     * @param array $hash
     * @return Spu_Entity_Classification_TipoTramitacao[]
     */
    protected function _loadManyFromHash($hash)
    {
        $tiposTramitacao = array();
        foreach ($hash as $hashTipoTramitacao) {
            $tiposTramitacao[] = $this->loadFromHash($hashTipoTramitacao[0]);
        }
        
        return $tiposTramitacao;
    }
}