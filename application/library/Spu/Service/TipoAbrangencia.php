<?php
/**
 * Classe para acessar os serviços de Tipo de Abrangência dos Processos do SPU
 *
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_TipoAbrangencia extends Spu_Service_Abstract
{
    /**
	 * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
	 * @var string
	 */
    private $_baseUrl = 'spu/tiposprocesso';
    
    /**
     * Retorna todas as opções de tipo de abrangência
     * 
     * @return Spu_Entity_Classification_TipoAbrangencia[]
     */
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/abrangencias/listar";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Abrangencias'][0]);
    }
    
    /**
     * Carrega o tipo de abrangência através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_TipoAbrangencia
     */
    public function loadFromHash($hash)
    {
        $tipoAbrangencia = new Spu_Entity_Classification_TipoAbrangencia();
        
        $tipoAbrangencia->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $tipoAbrangencia->setNome($this->_getHashValue($hash, 'nome'));
        $tipoAbrangencia->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $tipoAbrangencia;
    }
    
    /**
     * Carrega vários tipos de abrangência através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_TipoAbrangencia[]
     */
    protected function _loadManyFromHash($hash)
    {
        $tiposAbrangencia = array();
        foreach ($hash as $hashTipoAbrangencia) {
            $tiposAbrangencia[] = $this->loadFromHash($hashTipoAbrangencia[0]);
        }
        
        return $tiposAbrangencia;
    }
}